<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/connection.php';

function apiResponse(int $code, array $body): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function apiText(mixed $value, int $limit = 5000): string
{
    return is_string($value) ? mb_substr(trim($value), 0, $limit, 'UTF-8') : '';
}

function apiRole(array $allowed): int
{
    global $iBacolorConnection;
    $accountId = filter_var($_SESSION['account_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$accountId || !in_array($_SESSION['role'] ?? '', $allowed, true)) {
        apiResponse(401, ['error' => 'Sign in with an authorized account to continue.']);
    }

    $statement = $iBacolorConnection->prepare(
        'SELECT roles, status FROM accounts WHERE account_id = :id LIMIT 1'
    );
    $statement->execute(['id' => $accountId]);
    $account = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$account || $account['status'] !== 'active' || $account['roles'] !== $_SESSION['role'] || !in_array($account['roles'], $allowed, true)) {
        $_SESSION = [];
        session_destroy();
        apiResponse(401, ['error' => 'Your session expired. Please sign in again.']);
    }
    return (int) $accountId;
}

function apiRequireVerifiedResident(int $accountId): void
{
    global $iBacolorConnection;
    if (($_SESSION['role'] ?? '') !== 'resident') {
        apiResponse(403, ['error' => 'This action is available to verified residents only.']);
    }
    $statement = $iBacolorConnection->prepare(
        'SELECT verification FROM resident_profiles WHERE account_id=:id LIMIT 1'
    );
    $statement->execute(['id' => $accountId]);
    if ($statement->fetchColumn() !== 'verified') {
        apiResponse(403, ['error' => 'Your resident account must be verified before using this feature.']);
    }
}

function apiCsrf(): void
{
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || empty($_SESSION['portal_csrf_token']) || !hash_equals($_SESSION['portal_csrf_token'], $token)) {
        apiResponse(403, ['error' => 'Your session expired. Reload the page and try again.']);
    }
}

function apiInput(): array
{
    if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
        $input = json_decode(file_get_contents('php://input'), true);
        return is_array($input) ? $input : [];
    }
    return $_POST;
}

function apiUpload(?array $file, array $types): ?array
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')) {
        throw new RuntimeException('The file upload failed.');
    }
    if ($file['size'] < 1 || $file['size'] > 10 * 1024 * 1024) {
        throw new RuntimeException('Files must be 10 MB or smaller.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!in_array($mime, $types, true)) {
        throw new RuntimeException('This file type is not allowed.');
    }
    $directory = __DIR__ . '/storage/private';
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Private file storage is unavailable.');
    }
    $key = bin2hex(random_bytes(24));
    $path = $directory . '/' . $key;
    if (!move_uploaded_file($file['tmp_name'], $path)) {
        throw new RuntimeException('The file could not be saved.');
    }
    return [
        'key' => $key,
        'path' => $path,
        'name' => mb_substr(basename((string) $file['name']), 0, 255, 'UTF-8'),
        'mime' => $mime,
    ];
}

function apiUploadAt(?array $files, int|string $index, array $types): ?array
{
    if (!$files || !is_array($files['name'] ?? null) || !array_key_exists($index, $files['name'])) {
        return null;
    }
    $file = [];
    foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $field) {
        $file[$field] = $files[$field][$index] ?? null;
    }
    return apiUpload($file, $types);
}

function apiUploadMany(?array $files, array $types, int $maxFiles): array
{
    if (!$files || !is_array($files['name'] ?? null)) {
        return [];
    }
    $uploads = [];
    try {
        foreach (array_keys($files['name']) as $index) {
            if (($files['error'][$index] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (count($uploads) >= $maxFiles) {
                throw new RuntimeException("Upload no more than {$maxFiles} files.");
            }
            $upload = apiUploadAt($files, $index, $types);
            if ($upload) {
                $uploads[] = $upload;
            }
        }
    } catch (Throwable $exception) {
        foreach ($uploads as $upload) {
            if (is_file($upload['path'])) {
                unlink($upload['path']);
            }
        }
        throw $exception;
    }
    return $uploads;
}

function apiReference(string $prefix): string
{
    return $prefix . '-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
}

function apiDispatch(): never
{
    global $iBacolorConnection;
    $action = apiText($_GET['action'] ?? '', 80);
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($action === 'news.public' && $method === 'GET') {
        $articles = $iBacolorConnection->query(
            "SELECT article_id,title,content,category,published_at,(image_storage_key IS NOT NULL) AS has_image
             FROM news_articles
             WHERE status='published' AND published_at<=UTC_TIMESTAMP()
             ORDER BY published_at DESC,article_id DESC"
        )->fetchAll();
        apiResponse(200, ['articles' => $articles]);
    }

    if ($action === 'news.image' && $method === 'GET') {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $isAdmin = ($_SESSION['role'] ?? '') === 'admin';
        if ($isAdmin) {
            apiRole(['admin']);
        }
        $query = $isAdmin
            ? 'SELECT image_storage_key,image_mime_type FROM news_articles WHERE article_id=:id'
            : "SELECT image_storage_key,image_mime_type FROM news_articles WHERE article_id=:id AND status='published' AND published_at<=UTC_TIMESTAMP()";
        $statement = $iBacolorConnection->prepare($query);
        $statement->execute(['id' => $id]);
        $image = $statement->fetch();
        if (!$image || !preg_match('/^[a-f0-9]{48}$/', (string) $image['image_storage_key'])) {
            apiResponse(404, ['error' => 'Image not found.']);
        }
        $path = __DIR__ . '/storage/private/' . $image['image_storage_key'];
        if (!is_file($path) || !in_array($image['image_mime_type'], ['image/jpeg', 'image/png', 'image/webp'], true)) {
            apiResponse(404, ['error' => 'Image is unavailable.']);
        }
        header('Content-Type: ' . $image['image_mime_type']);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    if ($action === 'csrf' && $method === 'GET') {
        apiRole(['resident', 'admin']);
        $_SESSION['portal_csrf_token'] ??= bin2hex(random_bytes(32));
        apiResponse(200, ['token' => $_SESSION['portal_csrf_token']]);
    }
    if ($action === 'logout' && $method === 'POST') {
        apiRole(['resident', 'admin']);
        apiCsrf();
        $_SESSION = [];
        session_destroy();
        apiResponse(200, ['redirect' => '../logout.php']);
    }

    if ($action === 'download' && $method === 'GET') {
        $role = $_SESSION['role'] ?? '';
        $accountId = apiRole(['resident', 'admin']);
        $type = apiText($_GET['type'] ?? '', 20);
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || !in_array($type, ['request', 'report', 'verification', 'avatar'], true)) {
            apiResponse(404, ['error' => 'File not found.']);
        }
        if ($type === 'request') {
            $query = 'SELECT d.storage_key,d.document_name AS file_name,d.mime_type,sr.account_id
                FROM request_documents d JOIN service_requests sr ON sr.request_id=d.request_id WHERE d.document_id=:id';
        } elseif ($type === 'report') {
            $query = 'SELECT d.storage_key,d.file_name,d.mime_type,cr.account_id
                FROM report_attachments d JOIN community_reports cr ON cr.report_id=d.report_id WHERE d.attachment_id=:id';
        } elseif ($type === 'avatar') {
            $query = "SELECT profile_image_key AS storage_key,'avatar' AS file_name,'application/octet-stream' AS mime_type,account_id
                FROM resident_profiles WHERE account_id=:id AND profile_image_key IS NOT NULL";
        } else {
            $query = 'SELECT storage_key,file_name,mime_type,account_id
                FROM resident_verification_documents WHERE document_id=:id';
        }
        $statement = $iBacolorConnection->prepare($query);
        $statement->execute(['id' => $id]);
        $file = $statement->fetch();
        if (!$file || ($role === 'resident' && (int) $file['account_id'] !== $accountId) || !preg_match('/^[a-f0-9]{48}$/', $file['storage_key'])) {
            apiResponse(404, ['error' => 'File not found.']);
        }
        $path = __DIR__ . '/storage/private/' . $file['storage_key'];
        if (!is_file($path)) {
            apiResponse(404, ['error' => 'File is unavailable.']);
        }
        if ($type === 'avatar') {
            $file['mime_type'] = (new finfo(FILEINFO_MIME_TYPE))->file($path);
            if (!in_array($file['mime_type'], ['image/jpeg', 'image/png', 'image/webp'], true)) {
                apiResponse(404, ['error' => 'File not found.']);
            }
        }
        header('Content-Type: ' . $file['mime_type']);
        header('Content-Length: ' . filesize($path));
        $inlineFile = ($_GET['inline'] ?? '') === '1'
            && in_array($file['mime_type'], ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true);
        header($inlineFile
            ? 'Content-Disposition: inline'
            : 'Content-Disposition: attachment; filename="' . str_replace(['"', "\r", "\n"], '', basename($file['file_name'])) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    $accountId = apiRole(str_starts_with($action, 'admin.') ? ['admin'] : ['resident', 'admin']);
    if ($method === 'POST') {
        apiCsrf();
    }

    if ($action === 'resident.dashboard') {
        $stats = $iBacolorConnection->prepare(
            "SELECT COUNT(*) AS total,
                COALESCE(SUM(LOWER(status) IN ('submitted','under review','pending')),0) AS awaiting,
                COALESCE(SUM(LOWER(status) IN ('in progress','on process','reviewing')),0) AS in_progress,
                COALESCE(SUM(LOWER(status) IN ('resolved','done','closed','completed')),0) AS completed
             FROM service_requests WHERE account_id = :id"
        );
        $stats->execute(['id' => $accountId]);
        $recent = $iBacolorConnection->prepare(
            'SELECT sr.reference, sr.status, sr.submitted_at, COALESCE(sr.updated_at,sr.submitted_at) AS updated_at,s.name AS service_name
             FROM service_requests sr LEFT JOIN services s ON s.service_id=sr.service_id
             WHERE sr.account_id=:id ORDER BY sr.submitted_at DESC LIMIT 5'
        );
        $recent->execute(['id' => $accountId]);
        $profile = $iBacolorConnection->prepare(
            'SELECT rp.first_name,rp.last_name,b.name AS barangay_name FROM resident_profiles rp
             LEFT JOIN barangays b ON b.barangay_id=rp.barangay_id WHERE rp.account_id=:id'
        );
        $profile->execute(['id' => $accountId]);
        apiResponse(200, ['stats' => $stats->fetch(), 'requests' => $recent->fetchAll(), 'profile' => $profile->fetch()]);
    }
    if ($action === 'resident.requests') {
        $statement = $iBacolorConnection->prepare(
            'SELECT sr.reference,sr.status,sr.submitted_at,sr.updated_at,s.name AS service_name
             FROM service_requests sr LEFT JOIN services s ON s.service_id=sr.service_id
             WHERE sr.account_id=:id ORDER BY sr.submitted_at DESC'
        );
        $statement->execute(['id' => $accountId]);
        apiResponse(200, ['requests' => $statement->fetchAll()]);
    }
    if ($action === 'resident.services') {
        $statement = $iBacolorConnection->query(
            "SELECT service_id,code,name,category,office,description FROM services
             WHERE LOWER(status) IN ('available','active','published') ORDER BY name"
        );
        $services = $statement->fetchAll();
        $requirements = $iBacolorConnection->query(
            'SELECT requirement_id,service_id,requirement,display_order FROM service_requirements ORDER BY display_order,requirement_id'
        )->fetchAll();
        $byService = [];
        foreach ($requirements as $requirement) {
            $byService[$requirement['service_id']][] = [
                'requirement_id' => $requirement['requirement_id'],
                'requirement' => $requirement['requirement'],
            ];
        }
        foreach ($services as &$service) {
            $service['requirements'] = $byService[$service['service_id']] ?? [];
        }
        unset($service);
        apiResponse(200, ['services' => $services]);
    }
    if ($action === 'resident.barangays') {
        $statement = $iBacolorConnection->query('SELECT barangay_id,name FROM barangays ORDER BY name');
        apiResponse(200, ['barangays' => $statement->fetchAll()]);
    }
    if ($action === 'resident.request') {
        $statement = $iBacolorConnection->prepare(
            'SELECT sr.request_id,sr.reference,sr.details,sr.status,sr.submitted_at,sr.updated_at,s.name AS service_name
             FROM service_requests sr LEFT JOIN services s ON s.service_id=sr.service_id
             WHERE sr.reference=:reference AND sr.account_id=:id LIMIT 1'
        );
        $statement->execute(['reference' => apiText($_GET['reference'] ?? '', 100), 'id' => $accountId]);
        $request = $statement->fetch();
        if (!$request) {
            apiResponse(404, ['error' => 'Request not found.']);
        }
        $history = $iBacolorConnection->prepare('SELECT status,note,changed_at FROM request_status_history WHERE request_id=:id ORDER BY changed_at');
        $history->execute(['id' => $request['request_id']]);
        $documents = $iBacolorConnection->prepare(
            'SELECT d.document_id,d.document_name,d.mime_type,d.review_status,d.review_note,d.uploaded_at,
             NOT EXISTS(SELECT 1 FROM request_documents newer WHERE newer.request_id=d.request_id
               AND newer.document_name=d.document_name
               AND (newer.uploaded_at>d.uploaded_at OR (newer.uploaded_at=d.uploaded_at AND newer.document_id>d.document_id))) AS is_latest
             FROM request_documents d WHERE d.request_id=:id ORDER BY d.uploaded_at'
        );
        $documents->execute(['id' => $request['request_id']]);
        $appointments = $iBacolorConnection->prepare(
            'SELECT appointment_id,status,appointment_date,appointment_time,office,location,instructions,created_at
             FROM request_appointments WHERE request_id=:id ORDER BY appointment_id DESC'
        );
        $appointments->execute(['id' => $request['request_id']]);
        apiResponse(200, [
            'request' => $request,
            'history' => $history->fetchAll(),
            'documents' => $documents->fetchAll(),
            'appointments' => $appointments->fetchAll(),
        ]);
    }
    if ($action === 'resident.reports') {
        $statement = $iBacolorConnection->prepare(
            'SELECT cr.reference,cr.category,cr.location_details,cr.description,cr.observed_at,cr.frequency,cr.status,cr.submitted_at,b.name AS barangay_name
             FROM community_reports cr LEFT JOIN barangays b ON b.barangay_id=cr.barangay_id
             WHERE cr.account_id=:id ORDER BY cr.submitted_at DESC'
        );
        $statement->execute(['id' => $accountId]);
        $reports = $statement->fetchAll();
        $historyQuery = $iBacolorConnection->prepare(
            'SELECT status,note,changed_at FROM report_status_history WHERE report_id=:id ORDER BY changed_at'
        );
        $attachmentsQuery = $iBacolorConnection->prepare(
            'SELECT attachment_id,file_name,mime_type,uploaded_at FROM report_attachments WHERE report_id=:id ORDER BY uploaded_at'
        );
        foreach ($reports as &$report) {
            $findId = $iBacolorConnection->prepare(
                'SELECT report_id FROM community_reports WHERE reference=:reference AND account_id=:id'
            );
            $findId->execute(['reference' => $report['reference'], 'id' => $accountId]);
            $reportId = (int) $findId->fetchColumn();
            $historyQuery->execute(['id' => $reportId]);
            $attachmentsQuery->execute(['id' => $reportId]);
            $report['history'] = $historyQuery->fetchAll();
            $report['attachments'] = $attachmentsQuery->fetchAll();
        }
        unset($report);
        apiResponse(200, ['reports' => $reports]);
    }
    if ($action === 'resident.profile') {
        $statement = $iBacolorConnection->prepare(
            "SELECT a.email,a.created_at,rp.first_name,rp.middle_name,rp.last_name,rp.mobile_number,rp.barangay_id,b.name AS barangay_name,rp.address,rp.profile_image_key AS avatar_key,
             COALESCE((SELECT vh.status FROM resident_verification_history vh WHERE vh.account_id=a.account_id
                       ORDER BY vh.changed_at DESC,vh.history_id DESC LIMIT 1),rp.verification) AS verification_status,
             (SELECT vh.note FROM resident_verification_history vh WHERE vh.account_id=a.account_id
              ORDER BY vh.changed_at DESC,vh.history_id DESC LIMIT 1) AS verification_note,
             COALESCE(np.request_updates,1) AS request_updates,COALESCE(np.announcement_updates,1) AS announcement_updates
             FROM accounts a JOIN resident_profiles rp ON rp.account_id=a.account_id
             LEFT JOIN barangays b ON b.barangay_id=rp.barangay_id
             LEFT JOIN notification_preferences np ON np.account_id=a.account_id WHERE a.account_id=:id"
        );
        $statement->execute(['id' => $accountId]);
        $profile = $statement->fetch();
        if ($profile) {
            $profile['avatar_url'] = $profile['avatar_key']
                ? 'api.php?action=download&type=avatar&inline=1&id=' . $accountId . '&v=' . substr($profile['avatar_key'], 0, 12)
                : null;
            unset($profile['avatar_key']);
        }
        apiResponse(200, ['profile' => $profile]);
    }
    if ($action === 'resident.verification-documents') {
        $statement = $iBacolorConnection->prepare(
            'SELECT document_id,file_name,mime_type,uploaded_at FROM resident_verification_documents
             WHERE account_id=:id ORDER BY uploaded_at DESC'
        );
        $statement->execute(['id' => $accountId]);
        apiResponse(200, ['documents' => $statement->fetchAll()]);
    }
    if ($action === 'resident.upload-verification-documents' && $method === 'POST') {
        if (($_SESSION['role'] ?? '') !== 'resident') {
            apiResponse(403, ['error' => 'Only residents can submit verification documents.']);
        }
        $check = $iBacolorConnection->prepare('SELECT verification FROM resident_profiles WHERE account_id=:id LIMIT 1');
        $check->execute(['id' => $accountId]);
        if ($check->fetchColumn() === 'verified') {
            apiResponse(409, ['error' => 'Your account is already verified.']);
        }
        try {
            $uploads = apiUploadMany($_FILES['documents'] ?? null, ['application/pdf','image/jpeg','image/png'], 5);
        } catch (RuntimeException $exception) {
            apiResponse(422, ['error' => $exception->getMessage()]);
        }
        if (!$uploads) {
            apiResponse(422, ['error' => 'Choose at least one verification document.']);
        }
        try {
            $insert = $iBacolorConnection->prepare(
                'INSERT INTO resident_verification_documents(account_id,uploaded_by,file_name,storage_key,mime_type,uploaded_at)
                 VALUES(:account_id,:uploaded_by,:file_name,:storage_key,:mime_type,UTC_TIMESTAMP())'
            );
            foreach ($uploads as $upload) {
                $insert->execute([
                    'account_id' => $accountId, 'uploaded_by' => $accountId, 'file_name' => $upload['name'],
                    'storage_key' => $upload['key'], 'mime_type' => $upload['mime'],
                ]);
            }
        } catch (Throwable $exception) {
            foreach ($uploads as $upload) {
                if (is_file($upload['path'])) {
                    unlink($upload['path']);
                }
            }
            throw $exception;
        }
        apiResponse(201, ['message' => 'Verification documents submitted.']);
    }

    if ($action === 'resident.upload-avatar' && $method === 'POST') {
        if (($_SESSION['role'] ?? '') !== 'resident') {
            apiResponse(403, ['error' => 'Only residents can change a profile photo.']);
        }
        $file = $_FILES['avatar'] ?? null;
        if ($file && ($file['size'] ?? 0) > 2 * 1024 * 1024) {
            apiResponse(422, ['error' => 'Photo must be 2 MB or smaller.']);
        }
        try {
            $upload = apiUpload($file, ['image/jpeg', 'image/png', 'image/webp']);
        } catch (RuntimeException $exception) {
            apiResponse(422, ['error' => $exception->getMessage()]);
        }
        if (!$upload || @getimagesize($upload['path']) === false) {
            if ($upload && is_file($upload['path'])) {
                unlink($upload['path']);
            }
            apiResponse(422, ['error' => 'Choose a valid JPG, PNG, or WebP image.']);
        }
        try {
            $old = $iBacolorConnection->prepare('SELECT profile_image_key FROM resident_profiles WHERE account_id=:id LIMIT 1');
            $old->execute(['id' => $accountId]);
            $oldKey = $old->fetchColumn();
            $update = $iBacolorConnection->prepare(
                'UPDATE resident_profiles SET profile_image_key=:key WHERE account_id=:id'
            );
            $update->execute(['key' => $upload['key'], 'id' => $accountId]);
        } catch (Throwable $exception) {
            if (is_file($upload['path'])) {
                unlink($upload['path']);
            }
            throw $exception;
        }
        if (is_string($oldKey) && preg_match('/^[a-f0-9]{48}$/', $oldKey)) {
            $oldPath = __DIR__ . '/storage/private/' . $oldKey;
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        apiResponse(200, [
            'message' => 'Profile photo updated.',
            'avatar_url' => 'api.php?action=download&type=avatar&inline=1&id=' . $accountId . '&v=' . substr($upload['key'], 0, 12),
        ]);
    }

    if ($action === 'resident.submit-request' && $method === 'POST') {
        apiRequireVerifiedResident($accountId);
        $serviceId = filter_var($_POST['service'] ?? null, FILTER_VALIDATE_INT);
        $details = apiText($_POST['details'] ?? '', 256);
        if (!$serviceId || $details === '' || mb_strlen($details) > 255) {
            apiResponse(422, ['error' => 'Choose a service and enter request details (maximum 255 characters).']);
        }
        $service = $iBacolorConnection->prepare(
            "SELECT service_id FROM services WHERE service_id=:id AND LOWER(status) IN ('available','active','published')"
        );
        $service->execute(['id' => $serviceId]);
        if (!$service->fetchColumn()) {
            apiResponse(422, ['error' => 'Choose a valid service.']);
        }
        $requirements = $iBacolorConnection->prepare(
            'SELECT requirement_id,requirement FROM service_requirements WHERE service_id=:service_id ORDER BY display_order,requirement_id'
        );
        $requirements->execute(['service_id' => $serviceId]);
        $requiredDocuments = $requirements->fetchAll();
        $uploads = [];
        try {
            foreach ($requiredDocuments as $requiredDocument) {
                $upload = apiUploadAt($_FILES['documents'] ?? null, (int) $requiredDocument['requirement_id'], ['application/pdf','image/jpeg','image/png']);
                if (!$upload) {
                    throw new RuntimeException('Upload every document required for the selected service.');
                }
                $upload['document_name'] = $requiredDocument['requirement'];
                $uploads[] = $upload;
            }
        } catch (Throwable $exception) {
            foreach ($uploads as $existingUpload) {
                if (is_file($existingUpload['path'])) {
                    unlink($existingUpload['path']);
                }
            }
            if ($exception instanceof RuntimeException) {
                apiResponse(422, ['error' => $exception->getMessage()]);
            }
            throw $exception;
        }
        $reference = apiReference('IB');
        try {
            $iBacolorConnection->beginTransaction();
            $insert = $iBacolorConnection->prepare(
                "INSERT INTO service_requests(reference,account_id,service_id,details,status,submitted_at,updated_at)
                 VALUES(:reference,:account_id,:service_id,:details,'Submitted',UTC_TIMESTAMP(),UTC_TIMESTAMP())"
            );
            $insert->execute(['reference'=>$reference,'account_id'=>$accountId,'service_id'=>$serviceId,'details'=>$details]);
            $requestId = (int) $iBacolorConnection->lastInsertId();
            $history = $iBacolorConnection->prepare(
                "INSERT INTO request_status_history(request_id,changed_by,status,note,changed_at)
                 VALUES(:id,:changed_by,'Submitted','Request received.',UTC_TIMESTAMP())"
            );
            $history->execute(['id'=>$requestId,'changed_by'=>$accountId]);
            if ($uploads) {
                $document = $iBacolorConnection->prepare(
                    "INSERT INTO request_documents(request_id,uploaded_by,document_name,storage_key,mime_type,review_status,uploaded_at)
                     VALUES(:id,:user,:name,:key,:mime,'submitted',UTC_TIMESTAMP())"
                );
                foreach ($uploads as $upload) {
                    $document->execute([
                        'id' => $requestId, 'user' => $accountId, 'name' => $upload['document_name'],
                        'key' => $upload['key'], 'mime' => $upload['mime'],
                    ]);
                }
            }
            $iBacolorConnection->commit();
        } catch (Throwable $exception) {
            if ($iBacolorConnection->inTransaction()) $iBacolorConnection->rollBack();
            foreach ($uploads as $upload) {
                if (is_file($upload['path'])) {
                    unlink($upload['path']);
                }
            }
            throw $exception;
        }
        apiResponse(201, ['reference'=>$reference]);
    }
    if ($action === 'resident.upload-document' && $method === 'POST') {
        $lookup = $iBacolorConnection->prepare('SELECT request_id FROM service_requests WHERE reference=:reference AND account_id=:id');
        $lookup->execute(['reference'=>apiText($_POST['reference'] ?? '',100),'id'=>$accountId]);
        $requestId = $lookup->fetchColumn();
        if (!$requestId) apiResponse(404,['error'=>'Request not found.']);
        try {
            $upload = apiUpload($_FILES['document'] ?? null,['application/pdf','image/jpeg','image/png']);
        } catch (RuntimeException $exception) {
            apiResponse(422,['error'=>$exception->getMessage()]);
        }
        if (!$upload) apiResponse(422,['error'=>'Choose a file to upload.']);
        try {
            $insert = $iBacolorConnection->prepare(
                "INSERT INTO request_documents(request_id,uploaded_by,document_name,storage_key,mime_type,review_status,uploaded_at)
                 VALUES(:id,:user,:name,:key,:mime,'submitted',UTC_TIMESTAMP())"
            );
            $insert->execute(['id'=>$requestId,'user'=>$accountId,'name'=>$upload['name'],'key'=>$upload['key'],'mime'=>$upload['mime']]);
        } catch (Throwable $exception) {
            if (is_file($upload['path'])) unlink($upload['path']);
            throw $exception;
        }
        apiResponse(201,['message'=>'Document uploaded.']);
    }
    if ($action === 'resident.replace-document' && $method === 'POST') {
        $documentId = filter_var($_POST['documentId'] ?? null, FILTER_VALIDATE_INT);
        if (!$documentId) {
            apiResponse(422, ['error' => 'Choose a document to replace.']);
        }
        $lookup = $iBacolorConnection->prepare(
            "SELECT d.request_id,d.document_name,d.review_status
             FROM request_documents d JOIN service_requests sr ON sr.request_id=d.request_id
             WHERE d.document_id=:id AND sr.account_id=:account_id LIMIT 1"
        );
        $lookup->execute(['id' => $documentId, 'account_id' => $accountId]);
        $oldDocument = $lookup->fetch();
        if (!$oldDocument || $oldDocument['review_status'] !== 'needs correction') {
            apiResponse(404, ['error' => 'The document cannot be replaced.']);
        }
        $latest = $iBacolorConnection->prepare(
            'SELECT document_id FROM request_documents WHERE request_id=:request_id AND document_name=:name
             ORDER BY uploaded_at DESC,document_id DESC LIMIT 1'
        );
        $latest->execute(['request_id' => $oldDocument['request_id'], 'name' => $oldDocument['document_name']]);
        if ((int) $latest->fetchColumn() !== $documentId) {
            apiResponse(409, ['error' => 'A newer version of this document has already been submitted.']);
        }
        try {
            $upload = apiUpload($_FILES['document'] ?? null, ['application/pdf', 'image/jpeg', 'image/png']);
        } catch (RuntimeException $exception) {
            apiResponse(422, ['error' => $exception->getMessage()]);
        }
        if (!$upload) {
            apiResponse(422, ['error' => 'Choose a replacement file.']);
        }
        try {
            $insert = $iBacolorConnection->prepare(
                "INSERT INTO request_documents(request_id,uploaded_by,document_name,storage_key,mime_type,review_status,uploaded_at)
                 VALUES(:request_id,:user,:name,:key,:mime,'submitted',UTC_TIMESTAMP())"
            );
            $insert->execute([
                'request_id' => $oldDocument['request_id'], 'user' => $accountId,
                'name' => $oldDocument['document_name'], 'key' => $upload['key'], 'mime' => $upload['mime'],
            ]);
        } catch (Throwable $exception) {
            if (is_file($upload['path'])) {
                unlink($upload['path']);
            }
            throw $exception;
        }
        apiResponse(201, ['message' => 'Replacement document submitted for review.']);
    }
    if ($action === 'resident.submit-report' && $method === 'POST') {
        apiRequireVerifiedResident($accountId);
        $category=apiText($_POST['category'] ?? '',256);
        $barangay=filter_var($_POST['barangay'] ?? null,FILTER_VALIDATE_INT);
        $location=apiText($_POST['location'] ?? '',2000);
        $description=apiText($_POST['description'] ?? '');
        $frequency=apiText($_POST['frequency'] ?? '',101);
        $observedRaw=apiText($_POST['observedAt'] ?? '',30);
        if (!$barangay || $category==='' || mb_strlen($category)>255 || mb_strlen($frequency)>100 || $location==='' || $description==='') apiResponse(422,['error'=>'Check the category and frequency limits, then complete the barangay, location, and description fields.']);
        $validBarangay=$iBacolorConnection->prepare('SELECT 1 FROM barangays WHERE barangay_id=:id');
        $validBarangay->execute(['id'=>$barangay]);
        if (!$validBarangay->fetchColumn()) apiResponse(422,['error'=>'Choose a valid barangay.']);
        $observed=null;
        if ($observedRaw!=='') {
            $date=DateTime::createFromFormat('Y-m-d\TH:i',$observedRaw,new DateTimeZone('Asia/Manila'));
            if (!$date) apiResponse(422,['error'=>'Enter a valid observation date and time.']);
            $date->setTimezone(new DateTimeZone('UTC'));
            $observed=$date->format('Y-m-d H:i:s');
        }
        try {
            $uploads=apiUploadMany($_FILES['attachments'] ?? null,['application/pdf','image/jpeg','image/png','image/webp'],5);
        } catch (RuntimeException $exception) {
            apiResponse(422,['error'=>$exception->getMessage()]);
        }
        $reference=apiReference('CR');
        try {
            $iBacolorConnection->beginTransaction();
            $insert=$iBacolorConnection->prepare(
                "INSERT INTO community_reports(reference,account_id,category,barangay_id,location_details,description,observed_at,frequency,status,submitted_at,updated_at)
                 VALUES(:reference,:account_id,:category,:barangay,:location,:description,:observed,:frequency,'Submitted',UTC_TIMESTAMP(),UTC_TIMESTAMP())"
            );
            $insert->execute(['reference'=>$reference,'account_id'=>$accountId,'category'=>$category,'barangay'=>$barangay,'location'=>$location,'description'=>$description,'observed'=>$observed,'frequency'=>$frequency ?: null]);
            $reportId=(int)$iBacolorConnection->lastInsertId();
            $history=$iBacolorConnection->prepare(
                "INSERT INTO report_status_history(report_id,changed_by,status,note,changed_at)
                 VALUES(:id,:changed_by,'Submitted','Report received.',UTC_TIMESTAMP())"
            );
            $history->execute(['id'=>$reportId,'changed_by'=>$accountId]);
            foreach ($uploads as $upload) {
                $attachment=$iBacolorConnection->prepare(
                    'INSERT INTO report_attachments(report_id,uploaded_by,file_name,storage_key,mime_type,uploaded_at)
                     VALUES(:id,:user,:name,:key,:mime,UTC_TIMESTAMP())'
                );
                $attachment->execute(['id'=>$reportId,'user'=>$accountId,'name'=>$upload['name'],'key'=>$upload['key'],'mime'=>$upload['mime']]);
            }
            $iBacolorConnection->commit();
        } catch(Throwable $exception) {
            if($iBacolorConnection->inTransaction()) $iBacolorConnection->rollBack();
            foreach ($uploads as $upload) {
                if (is_file($upload['path'])) {
                    unlink($upload['path']);
                }
            }
            throw $exception;
        }
        apiResponse(201,['reference'=>$reference]);
    }
    if ($action === 'resident.save-profile' && $method === 'POST') {
        $input=apiInput();
        $first=apiText($input['firstName'] ?? '',255);
        $last=apiText($input['lastName'] ?? '',255);
        $mobile=apiText($input['mobile'] ?? '',20);
        $address=apiText($input['address'] ?? '',256);
        $barangay=filter_var($input['barangay'] ?? null,FILTER_VALIDATE_INT);
        if($first==='' || $last==='' || $address==='' || mb_strlen($address)>255 || !$barangay || preg_match('/^(?:\+639|09)[0-9]{9}$/',$mobile)!==1) apiResponse(422,['error'=>'Check your name, mobile number, barangay, and address (maximum 255 characters).']);
        $update=$iBacolorConnection->prepare('UPDATE resident_profiles SET first_name=:first,last_name=:last,mobile_number=:mobile,barangay_id=:barangay,address=:address WHERE account_id=:id');
        $update->execute(['first'=>$first,'last'=>$last,'mobile'=>$mobile,'barangay'=>$barangay,'address'=>$address,'id'=>$accountId]);
        apiResponse(200,['message'=>'Profile saved.']);
    }
    if ($action === 'resident.save-preferences' && $method === 'POST') {
        $input=apiInput();
        $statement=$iBacolorConnection->prepare(
            'INSERT INTO notification_preferences(account_id,request_updates,announcement_updates)
             VALUES(:id,:requests,:announcements)
             ON DUPLICATE KEY UPDATE request_updates=VALUES(request_updates),announcement_updates=VALUES(announcement_updates)'
        );
        $statement->execute(['id'=>$accountId,'requests'=>!empty($input['requestUpdates'])?1:0,'announcements'=>!empty($input['announcementUpdates'])?1:0]);
        apiResponse(200,['message'=>'Notification preferences saved.']);
    }
    if ($action === 'resident.save-settings' && $method === 'POST') {
        $input=apiInput();
        $current = is_string($input['currentPassword'] ?? null) ? $input['currentPassword'] : '';
        $newPassword = is_string($input['newPassword'] ?? null) ? $input['newPassword'] : '';
        $confirm = is_string($input['confirmPassword'] ?? null) ? $input['confirmPassword'] : '';
        $account = $iBacolorConnection->prepare('SELECT password_hash FROM accounts WHERE account_id=:id');
        $account->execute(['id'=>$accountId]);
        $hash = $account->fetchColumn();
        if (!$hash || !password_verify($current, $hash)) apiResponse(422,['error'=>'Your current password is incorrect.']);
        if (preg_match('/^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/',$newPassword)!==1 || $newPassword!==$confirm) {
            apiResponse(422,['error'=>'The new passwords must match and include at least 8 characters, a number, and a symbol.']);
        }
        $iBacolorConnection->beginTransaction();
        $statement=$iBacolorConnection->prepare(
            'INSERT INTO notification_preferences(account_id,request_updates,announcement_updates)
             VALUES(:id,:requests,:announcements)
             ON DUPLICATE KEY UPDATE request_updates=VALUES(request_updates),announcement_updates=VALUES(announcement_updates)'
        );
        $statement->execute(['id'=>$accountId,'requests'=>!empty($input['requestUpdates'])?1:0,'announcements'=>!empty($input['announcementUpdates'])?1:0]);
        $passwordUpdate=$iBacolorConnection->prepare('UPDATE accounts SET password_hash=:password_hash WHERE account_id=:id');
        $passwordUpdate->execute(['password_hash'=>password_hash($newPassword,PASSWORD_DEFAULT),'id'=>$accountId]);
        $iBacolorConnection->commit();
        apiResponse(200,['message'=>'Notification preferences saved.']);
    }

    if ($action === 'admin.dashboard') {
        $requests=$iBacolorConnection->query(
            "SELECT COUNT(*) total,COALESCE(SUM(LOWER(status) IN ('submitted','under review','pending')),0) awaiting,
             COALESCE(SUM(LOWER(status) IN ('in progress','on process','reviewing')),0) in_progress FROM service_requests"
        )->fetch();
        $reports=$iBacolorConnection->query(
            "SELECT COUNT(*) total,COALESCE(SUM(LOWER(status) NOT IN ('resolved','done','closed','completed')),0) open FROM community_reports"
        )->fetch();
        $residents=(int)$iBacolorConnection->query("SELECT COUNT(*) FROM accounts WHERE roles='resident'")->fetchColumn();
        $recent=$iBacolorConnection->query(
            "SELECT sr.reference,sr.status,s.name service_name,COALESCE(NULLIF(CONCAT_WS(' ',rp.first_name,rp.last_name),''),a.email) applicant_name
             FROM service_requests sr JOIN accounts a ON a.account_id=sr.account_id
             LEFT JOIN resident_profiles rp ON rp.account_id=a.account_id LEFT JOIN services s ON s.service_id=sr.service_id
             ORDER BY sr.submitted_at DESC LIMIT 5"
        )->fetchAll();
        apiResponse(200,['requests'=>$requests,'reports'=>$reports,'residents'=>$residents,'recent'=>$recent]);
    }
    if ($action === 'admin.news') {
        $articles = $iBacolorConnection->query(
            'SELECT article_id,title,content,category,published_at,status,(image_storage_key IS NOT NULL) AS has_image,created_at,updated_at
             FROM news_articles ORDER BY updated_at DESC,article_id DESC'
        )->fetchAll();
        apiResponse(200, ['articles' => $articles]);
    }
    if ($action === 'admin.save-news' && $method === 'POST') {
        $input = apiInput();
        $id = filter_var($input['articleId'] ?? null, FILTER_VALIDATE_INT) ?: null;
        $title = apiText($input['title'] ?? '', 256);
        $content = apiText($input['content'] ?? '', 100000);
        $category = apiText($input['category'] ?? '', 101);
        $status = apiText($input['status'] ?? 'draft', 20);
        $publishedAtInput = apiText($input['publishedAt'] ?? '', 30);
        if ($title === '' || $content === '' || $category === '' || mb_strlen($title) > 255 || mb_strlen($category) > 100
            || !in_array($status, ['draft', 'published'], true)) {
            apiResponse(422, ['error' => 'Enter a title, article content, category, and valid publication status.']);
        }
        if (!in_array($category, [
            'Legal and Regulatory Notices',
            'Government and Administrative Announcements',
            'Public Safety and Emergency Advisories',
            'Community and Cultural Events',
        ], true)) {
            apiResponse(422, ['error' => 'Choose a valid announcement type.']);
        }
        $publishedAt = null;
        if ($status === 'published') {
            if ($publishedAtInput === '') {
                $publishedAt = gmdate('Y-m-d H:i:s');
            } else {
                $date = DateTime::createFromFormat('Y-m-d\TH:i', $publishedAtInput, new DateTimeZone('Asia/Manila'));
                if (!$date) {
                    apiResponse(422, ['error' => 'Enter a valid publication date and time.']);
                }
                $date->setTimezone(new DateTimeZone('UTC'));
                $publishedAt = $date->format('Y-m-d H:i:s');
            }
        }
        $upload = apiUpload($_FILES['image'] ?? null, ['image/jpeg', 'image/png', 'image/webp']);
        $oldImageKey = null;
        try {
            if ($id) {
                $existing = $iBacolorConnection->prepare('SELECT image_storage_key FROM news_articles WHERE article_id=:id');
                $existing->execute(['id' => $id]);
                $oldImageKey = $existing->fetchColumn();
                if ($oldImageKey === false) {
                    if ($upload && is_file($upload['path'])) {
                        unlink($upload['path']);
                    }
                    apiResponse(404, ['error' => 'News article not found.']);
                }
                $statement = $iBacolorConnection->prepare(
                    'UPDATE news_articles SET title=:title,content=:content,category=:category,published_at=:published_at,status=:status,
                     image_storage_key=COALESCE(:image_key,image_storage_key),image_mime_type=COALESCE(:image_mime,image_mime_type)
                     WHERE article_id=:id'
                );
                $statement->execute([
                    'title' => $title, 'content' => $content, 'category' => $category, 'published_at' => $publishedAt,
                    'status' => $status, 'image_key' => $upload['key'] ?? null, 'image_mime' => $upload['mime'] ?? null, 'id' => $id,
                ]);
            } else {
                $statement = $iBacolorConnection->prepare(
                    'INSERT INTO news_articles(title,content,category,published_at,status,image_storage_key,image_mime_type,created_by)
                     VALUES(:title,:content,:category,:published_at,:status,:image_key,:image_mime,:created_by)'
                );
                $statement->execute([
                    'title' => $title, 'content' => $content, 'category' => $category, 'published_at' => $publishedAt,
                    'status' => $status, 'image_key' => $upload['key'] ?? null, 'image_mime' => $upload['mime'] ?? null, 'created_by' => $accountId,
                ]);
                $id = (int) $iBacolorConnection->lastInsertId();
            }
        } catch (Throwable $exception) {
            if ($upload && is_file($upload['path'])) {
                unlink($upload['path']);
            }
            throw $exception;
        }
        if ($upload && $oldImageKey && $oldImageKey !== $upload['key']) {
            $oldPath = __DIR__ . '/storage/private/' . $oldImageKey;
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        apiResponse(200, ['message' => 'News article saved.', 'articleId' => $id]);
    }
    if ($action === 'admin.delete-news' && $method === 'POST') {
        $input = apiInput();
        $id = filter_var($input['articleId'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            apiResponse(422, ['error' => 'Choose a valid news article.']);
        }
        $find = $iBacolorConnection->prepare('SELECT image_storage_key FROM news_articles WHERE article_id=:id');
        $find->execute(['id' => $id]);
        $imageKey = $find->fetchColumn();
        if ($imageKey === false) {
            apiResponse(404, ['error' => 'News article not found.']);
        }
        $delete = $iBacolorConnection->prepare('DELETE FROM news_articles WHERE article_id=:id');
        $delete->execute(['id' => $id]);
        $imagePath = __DIR__ . '/storage/private/' . $imageKey;
        if ($imageKey && is_file($imagePath)) {
            unlink($imagePath);
        }
        apiResponse(200, ['message' => 'News article deleted.']);
    }
    if ($action === 'admin.requests') {
        $rows=$iBacolorConnection->query(
            "SELECT sr.reference,sr.status,sr.submitted_at,s.name service_name,
             COALESCE(NULLIF(CONCAT_WS(' ',rp.first_name,rp.last_name),''),a.email) applicant_name
             FROM service_requests sr JOIN accounts a ON a.account_id=sr.account_id
             LEFT JOIN resident_profiles rp ON rp.account_id=a.account_id LEFT JOIN services s ON s.service_id=sr.service_id ORDER BY sr.submitted_at DESC"
        )->fetchAll();
        apiResponse(200,['requests'=>$rows]);
    }
    if ($action === 'admin.reports') {
        $rows=$iBacolorConnection->query(
            "SELECT cr.reference,cr.category,cr.location_details,cr.description,cr.observed_at,cr.frequency,cr.status,cr.submitted_at,b.name barangay_name,
             COALESCE(NULLIF(CONCAT_WS(' ',rp.first_name,rp.last_name),''),a.email) applicant_name
             FROM community_reports cr JOIN accounts a ON a.account_id=cr.account_id
             LEFT JOIN resident_profiles rp ON rp.account_id=a.account_id LEFT JOIN barangays b ON b.barangay_id=cr.barangay_id
             ORDER BY cr.submitted_at DESC"
        )->fetchAll();
        apiResponse(200,['reports'=>$rows]);
    }
    if ($action === 'admin.report-files') {
        $statement=$iBacolorConnection->prepare(
            'SELECT a.attachment_id,a.file_name,a.mime_type,a.uploaded_at
             FROM report_attachments a JOIN community_reports cr ON cr.report_id=a.report_id
             WHERE cr.reference=:reference ORDER BY a.uploaded_at'
        );
        $statement->execute(['reference'=>apiText($_GET['reference'] ?? '',100)]);
        apiResponse(200,['attachments'=>$statement->fetchAll()]);
    }
    if ($action === 'admin.documents') {
        $rows=$iBacolorConnection->query(
            "SELECT d.document_id,d.document_name,d.mime_type,d.review_status,d.review_note,d.uploaded_at,sr.reference,
             COALESCE(NULLIF(CONCAT_WS(' ',rp.first_name,rp.last_name),''),a.email) owner_name
             FROM request_documents d JOIN service_requests sr ON sr.request_id=d.request_id
             JOIN accounts a ON a.account_id=sr.account_id LEFT JOIN resident_profiles rp ON rp.account_id=a.account_id ORDER BY d.uploaded_at DESC"
        )->fetchAll();
        apiResponse(200,['documents'=>$rows]);
    }
    if ($action === 'admin.request') {
        $statement=$iBacolorConnection->prepare(
            "SELECT sr.request_id,sr.reference,sr.details,sr.status,sr.submitted_at,sr.updated_at,sr.service_id,s.name service_name,
             COALESCE(NULLIF(CONCAT_WS(' ',rp.first_name,rp.last_name),''),a.email) applicant_name,rp.mobile_number,b.name barangay_name
             FROM service_requests sr JOIN accounts a ON a.account_id=sr.account_id
             LEFT JOIN resident_profiles rp ON rp.account_id=a.account_id LEFT JOIN barangays b ON b.barangay_id=rp.barangay_id
             LEFT JOIN services s ON s.service_id=sr.service_id WHERE sr.reference=:reference"
        );
        $statement->execute(['reference'=>apiText($_GET['reference'] ?? '',100)]);
        $request=$statement->fetch();
        if(!$request) apiResponse(404,['error'=>'Request not found.']);
        $history=$iBacolorConnection->prepare('SELECT status,note,changed_at FROM request_status_history WHERE request_id=:id ORDER BY changed_at');
        $history->execute(['id'=>$request['request_id']]);
        $documents=$iBacolorConnection->prepare('SELECT document_id,document_name,mime_type,review_status,review_note,uploaded_at FROM request_documents WHERE request_id=:id ORDER BY uploaded_at');
        $documents->execute(['id'=>$request['request_id']]);
        $requirements = $iBacolorConnection->prepare(
            'SELECT requirement_id,requirement,display_order FROM service_requirements WHERE service_id=:service_id ORDER BY display_order,requirement_id'
        );
        $requirements->execute(['service_id' => $request['service_id']]);
        $appointments = $iBacolorConnection->prepare(
            'SELECT appointment_id,status,appointment_date,appointment_time,office,location,instructions,created_at
             FROM request_appointments WHERE request_id=:id ORDER BY appointment_id DESC'
        );
        $appointments->execute(['id' => $request['request_id']]);
        apiResponse(200, [
            'request' => $request,
            'history' => $history->fetchAll(),
            'documents' => $documents->fetchAll(),
            'requirements' => $requirements->fetchAll(),
            'appointments' => $appointments->fetchAll(),
        ]);
    }
    if ($action === 'admin.update-request' && $method === 'POST') {
        $input=apiInput();
        $reference=apiText($input['reference'] ?? '',100);
        $statusInput=strtolower(apiText($input['status'] ?? '',50));
        $status=[
            'submitted'=>'Submitted','under review'=>'Under Review','in progress'=>'In Progress',
            'for revision'=>'For Revision','completed'=>'Completed','rejected'=>'Rejected',
        ][$statusInput] ?? '';
        $note=apiText($input['note'] ?? '',2000);
        if(!in_array($status,['Submitted','Under Review','In Progress','For Revision','Completed','Rejected'],true)) apiResponse(422,['error'=>'Choose a valid request status.']);
        if($status==='Rejected' && $note==='') apiResponse(422,['error'=>'Enter a rejection reason before rejecting the request.']);
        $iBacolorConnection->beginTransaction();
        $find=$iBacolorConnection->prepare('SELECT request_id FROM service_requests WHERE reference=:reference FOR UPDATE');
        $find->execute(['reference'=>$reference]);
        $requestId=$find->fetchColumn();
        if(!$requestId){$iBacolorConnection->rollBack();apiResponse(404,['error'=>'Request not found.']);}
        $update=$iBacolorConnection->prepare('UPDATE service_requests SET status=:status,updated_at=UTC_TIMESTAMP() WHERE request_id=:id');
        $update->execute(['status'=>$status,'id'=>$requestId]);
        $history=$iBacolorConnection->prepare('INSERT INTO request_status_history(request_id,changed_by,status,note,changed_at) VALUES(:id,:user,:status,:note,UTC_TIMESTAMP())');
        $history->execute(['id'=>$requestId,'user'=>$accountId,'status'=>$status,'note'=>$note ?: null]);
        $iBacolorConnection->commit();
        apiResponse(200,['message'=>'Request updated.']);
    }
    if ($action === 'admin.schedule-appointment' && $method === 'POST') {
        $input = apiInput();
        $reference = apiText($input['reference'] ?? '', 100);
        $dateInput = apiText($input['date'] ?? '', 10);
        $timeInput = apiText($input['time'] ?? '', 5);
        $office = apiText($input['office'] ?? '', 256);
        $location = apiText($input['location'] ?? '', 256);
        $instructions = apiText($input['instructions'] ?? '', 2000);
        $date = DateTime::createFromFormat('!Y-m-d', $dateInput);
        $time = DateTime::createFromFormat('!H:i', $timeInput);
        if (!$reference || !$date || $date->format('Y-m-d') !== $dateInput
            || !$time || $time->format('H:i') !== $timeInput
            || $office === '' || $location === ''
            || mb_strlen($office) > 255 || mb_strlen($location) > 255) {
            apiResponse(422, ['error' => 'Enter a valid appointment date, time, office, and location.']);
        }
        $iBacolorConnection->beginTransaction();
        $requestLookup = $iBacolorConnection->prepare(
            'SELECT request_id FROM service_requests WHERE reference=:reference FOR UPDATE'
        );
        $requestLookup->execute(['reference' => $reference]);
        $requestId = $requestLookup->fetchColumn();
        if (!$requestId) {
            $iBacolorConnection->rollBack();
            apiResponse(404, ['error' => 'Request not found.']);
        }
        $current = $iBacolorConnection->prepare(
            "SELECT appointment_id FROM request_appointments
             WHERE request_id=:id AND status IN ('scheduled','rescheduled')
             ORDER BY appointment_id DESC LIMIT 1 FOR UPDATE"
        );
        $current->execute(['id' => $requestId]);
        $currentId = $current->fetchColumn();
        $status = 'scheduled';
        if ($currentId) {
            $markRescheduled = $iBacolorConnection->prepare(
                "UPDATE request_appointments SET status='rescheduled' WHERE appointment_id=:id"
            );
            $markRescheduled->execute(['id' => $currentId]);
        }
        $insert = $iBacolorConnection->prepare(
            'INSERT INTO request_appointments(request_id,changed_by,status,appointment_date,appointment_time,office,location,instructions)
             VALUES(:request_id,:changed_by,:status,:appointment_date,:appointment_time,:office,:location,:instructions)'
        );
        $insert->execute([
            'request_id' => $requestId, 'changed_by' => $accountId, 'status' => $status,
            'appointment_date' => $dateInput, 'appointment_time' => $timeInput . ':00',
            'office' => $office, 'location' => $location, 'instructions' => $instructions ?: null,
        ]);
        $iBacolorConnection->commit();
        apiResponse(201, ['message' => 'Appointment saved.', 'status' => $status]);
    }
    if ($action === 'admin.update-appointment' && $method === 'POST') {
        $input = apiInput();
        $appointmentId = filter_var($input['appointmentId'] ?? null, FILTER_VALIDATE_INT);
        $status = apiText($input['status'] ?? '', 20);
        if (!$appointmentId || !in_array($status, ['cancelled', 'completed'], true)) {
            apiResponse(422, ['error' => 'Choose a valid appointment action.']);
        }
        $appointment = $iBacolorConnection->prepare(
            'SELECT request_id FROM request_appointments WHERE appointment_id=:id'
        );
        $appointment->execute(['id' => $appointmentId]);
        $requestId = $appointment->fetchColumn();
        if (!$requestId) {
            apiResponse(404, ['error' => 'Active appointment not found.']);
        }
        $iBacolorConnection->beginTransaction();
        $requestLock = $iBacolorConnection->prepare(
            'SELECT request_id FROM service_requests WHERE request_id=:id FOR UPDATE'
        );
        $requestLock->execute(['id' => $requestId]);
        if (!$requestLock->fetchColumn()) {
            $iBacolorConnection->rollBack();
            apiResponse(404, ['error' => 'Active appointment not found.']);
        }
        $current = $iBacolorConnection->prepare(
            "SELECT appointment_id FROM request_appointments
             WHERE request_id=:id AND status IN ('scheduled','rescheduled')
             ORDER BY appointment_id DESC LIMIT 1 FOR UPDATE"
        );
        $current->execute(['id' => $requestId]);
        if ((int) $current->fetchColumn() !== (int) $appointmentId) {
            $iBacolorConnection->rollBack();
            apiResponse(409, ['error' => 'Only the current active appointment can be updated.']);
        }
        $update = $iBacolorConnection->prepare(
            "UPDATE request_appointments SET status=:status,changed_by=:changed_by
             WHERE appointment_id=:id AND status IN ('scheduled','rescheduled')"
        );
        $update->execute(['status' => $status, 'changed_by' => $accountId, 'id' => $appointmentId]);
        if (!$update->rowCount()) {
            $iBacolorConnection->rollBack();
            apiResponse(404, ['error' => 'Active appointment not found.']);
        }
        $iBacolorConnection->commit();
        apiResponse(200, ['message' => 'Appointment updated.']);
    }
    if ($action === 'admin.review-document' && $method === 'POST') {
        $input=apiInput();
        $id=filter_var($input['documentId'] ?? null,FILTER_VALIDATE_INT);
        $status=apiText($input['status'] ?? '',30);
        if(!$id || !in_array($status,['submitted','accepted','needs correction'],true)) apiResponse(422,['error'=>'Choose a valid document review status.']);
        $note=apiText($input['note'] ?? '',2000);
        if($status==='needs correction' && $note==='') apiResponse(422,['error'=>'Explain what needs to be corrected for this document.']);
        $update=$iBacolorConnection->prepare('UPDATE request_documents SET review_status=:status,review_note=:note WHERE document_id=:id');
        $update->execute(['status'=>$status,'note'=>$note ?: null,'id'=>$id]);
        if(!$update->rowCount()) {
            $check=$iBacolorConnection->prepare('SELECT 1 FROM request_documents WHERE document_id=:id');
            $check->execute(['id'=>$id]);
            if(!$check->fetchColumn()) apiResponse(404,['error'=>'Document not found.']);
        }
        apiResponse(200,['message'=>'Document review saved.']);
    }
    if ($action === 'admin.update-report' && $method === 'POST') {
        $input=apiInput();
        $reference=apiText($input['reference'] ?? '',100);
        $statusInput=strtolower(apiText($input['status'] ?? '',50));
        $status=['submitted'=>'Submitted','under review'=>'Under Review','in progress'=>'In Progress','resolved'=>'Resolved','closed'=>'Closed','rejected'=>'Rejected'][$statusInput] ?? '';
        $note=apiText($input['note'] ?? '',2000);
        if(!in_array($status,['Submitted','Under Review','In Progress','Resolved','Closed','Rejected'],true)) apiResponse(422,['error'=>'Choose a valid report status.']);
        if($status==='Rejected' && $note==='') apiResponse(422,['error'=>'Enter a reason before rejecting the report.']);
        $iBacolorConnection->beginTransaction();
        $find=$iBacolorConnection->prepare('SELECT report_id FROM community_reports WHERE reference=:reference FOR UPDATE');
        $find->execute(['reference'=>$reference]);
        $reportId=$find->fetchColumn();
        if(!$reportId){$iBacolorConnection->rollBack();apiResponse(404,['error'=>'Report not found.']);}
        $update=$iBacolorConnection->prepare('UPDATE community_reports SET status=:status,updated_at=UTC_TIMESTAMP() WHERE report_id=:id');
        $update->execute(['status'=>$status,'id'=>$reportId]);
        $history=$iBacolorConnection->prepare('INSERT INTO report_status_history(report_id,changed_by,status,note,changed_at) VALUES(:id,:user,:status,:note,UTC_TIMESTAMP())');
        $history->execute(['id'=>$reportId,'user'=>$accountId,'status'=>$status,'note'=>$note ?: null]);
        $iBacolorConnection->commit();
        apiResponse(200,['message'=>'Report updated.']);
    }
    if ($action === 'admin.users') {
        $rows=$iBacolorConnection->query(
            "SELECT a.account_id,a.email,a.status,a.created_at,rp.first_name,rp.last_name,
             COALESCE((SELECT vh.status FROM resident_verification_history vh WHERE vh.account_id=a.account_id
                       ORDER BY vh.changed_at DESC,vh.history_id DESC LIMIT 1),rp.verification) verification,
             (SELECT vh.note FROM resident_verification_history vh WHERE vh.account_id=a.account_id
              ORDER BY vh.changed_at DESC,vh.history_id DESC LIMIT 1) verification_note,
             b.name barangay_name
             FROM accounts a JOIN resident_profiles rp ON rp.account_id=a.account_id
             LEFT JOIN barangays b ON b.barangay_id=rp.barangay_id WHERE a.roles='resident' ORDER BY a.created_at DESC"
        )->fetchAll();
        apiResponse(200,['users'=>$rows]);
    }
    if ($action === 'admin.user-documents') {
        $target = filter_var($_GET['accountId'] ?? null, FILTER_VALIDATE_INT);
        if (!$target) {
            apiResponse(422, ['error' => 'Choose a valid resident account.']);
        }
        $statement = $iBacolorConnection->prepare(
            "SELECT d.document_id,d.file_name,d.mime_type,d.uploaded_at
             FROM resident_verification_documents d
             JOIN accounts a ON a.account_id=d.account_id
             WHERE d.account_id=:id AND a.roles='resident'
             ORDER BY d.uploaded_at DESC"
        );
        $statement->execute(['id' => $target]);
        apiResponse(200, ['documents' => $statement->fetchAll()]);
    }
    if ($action === 'admin.update-user' && $method === 'POST') {
        $input=apiInput();
        $target=filter_var($input['accountId'] ?? null,FILTER_VALIDATE_INT);
        $hasStatus = array_key_exists('status', $input);
        $hasVerification = array_key_exists('verification', $input);
        $status=apiText($input['status'] ?? '',20);
        $verification=apiText($input['verification'] ?? '',20);
        $note=apiText($input['note'] ?? '',2000);
        if(!$target || (!$hasStatus && !$hasVerification)
            || ($hasStatus && !in_array($status,['active','pending','suspended'],true))
            || ($hasVerification && !in_array($verification,['pending','verified','rejected'],true))
            || ($verification==='rejected' && $note==='')) {
            apiResponse(422,['error'=>'Choose valid account and verification statuses. A rejection requires a reason.']);
        }
        $iBacolorConnection->beginTransaction();
        $exists=$iBacolorConnection->prepare("SELECT 1 FROM accounts WHERE account_id=:id AND roles='resident' FOR UPDATE");
        $exists->execute(['id'=>$target]);
        if(!$exists->fetchColumn()) {
            $iBacolorConnection->rollBack();
            apiResponse(404,['error'=>'Resident account not found.']);
        }
        if ($hasStatus) {
            $update=$iBacolorConnection->prepare("UPDATE accounts SET status=:status WHERE account_id=:id AND roles='resident'");
            $update->execute(['status'=>$status,'id'=>$target]);
        }
        if ($hasVerification) {
            $profileStatus = $verification === 'verified' ? 'verified' : 'pending';
            $profile=$iBacolorConnection->prepare('UPDATE resident_profiles SET verification=:verification WHERE account_id=:id');
            $profile->execute(['verification'=>$profileStatus,'id'=>$target]);
            $history=$iBacolorConnection->prepare(
                'INSERT INTO resident_verification_history(account_id,changed_by,status,note,changed_at)
                 VALUES(:account_id,:changed_by,:status,:note,UTC_TIMESTAMP())'
            );
            $history->execute([
                'account_id'=>$target,'changed_by'=>$accountId,'status'=>$verification,'note'=>$note ?: null,
            ]);
        }
        $iBacolorConnection->commit();
        apiResponse(200,['message'=>$hasVerification ? 'Resident verification updated.' : 'Account status updated.']);
    }
    if ($action === 'admin.services') {
        $rows=$iBacolorConnection->query(
            'SELECT service_id,code,name,description,category,office,status,fee_information,processing_info,instructions
             FROM services ORDER BY name'
        )->fetchAll();
        $requirements=$iBacolorConnection->query(
            'SELECT service_id,requirement,display_order FROM service_requirements ORDER BY display_order,requirement_id'
        )->fetchAll();
        $byService=[];
        foreach($requirements as $requirement) {
            $byService[$requirement['service_id']][]=$requirement['requirement'];
        }
        foreach($rows as &$service) {
            $service['requirements']=$byService[$service['service_id']] ?? [];
        }
        unset($service);
        apiResponse(200,['services'=>$rows]);
    }
    if ($action === 'admin.save-service' && $method === 'POST') {
        $input=apiInput();
        $id=filter_var($input['serviceId'] ?? null,FILTER_VALIDATE_INT) ?: null;
        $name=apiText($input['name'] ?? '',256);
        $code=apiText($input['code'] ?? '',51);
        $description=apiText($input['description'] ?? '',501);
        $category=apiText($input['category'] ?? '',256);
        $office=apiText($input['office'] ?? '',256);
        $statusInput=strtolower(apiText($input['status'] ?? 'Available',50));
        $status=['available'=>'Available','unavailable'=>'Unavailable','in review'=>'In review'][$statusInput] ?? '';
        if($name==='' || $code==='' || $description==='' || $category==='' || $office==='' || !in_array($status,['Available','Unavailable','In review'],true)) apiResponse(422,['error'=>'Complete all required service fields.']);
        $values=[
            'code'=>$code,'name'=>$name,'description'=>$description,'category'=>$category,'office'=>$office,'status'=>$status,
            'fee'=>apiText($input['fee_information'] ?? '',256) ?: null,
            'processing'=>apiText($input['processing_info'] ?? '',256) ?: null,
            'instructions'=>apiText($input['instructions'] ?? '',256) ?: null,
        ];
        if(mb_strlen($name)>255 || mb_strlen($code)>50 || mb_strlen($description)>500 || mb_strlen($category)>255 || mb_strlen($office)>255
            || mb_strlen((string)$values['fee'])>255 || mb_strlen((string)$values['processing'])>255 || mb_strlen((string)$values['instructions'])>255) {
            apiResponse(422,['error'=>'One or more service fields exceed their database field length.']);
        }
        $requirements=$input['requirements'] ?? [];
        if(!is_array($requirements)) apiResponse(422,['error'=>'Service requirements must be a list.']);
        $requirements=array_values(array_filter(array_map(
            static fn($requirement): string => apiText($requirement,255),
            $requirements
        ),static fn(string $requirement): bool => $requirement!==''));
        try {
            $iBacolorConnection->beginTransaction();
            if($id) {
                $statement=$iBacolorConnection->prepare('UPDATE services SET code=:code,name=:name,description=:description,category=:category,office=:office,status=:status,fee_information=:fee,processing_info=:processing,instructions=:instructions WHERE service_id=:id');
                $values['id']=$id;
                $statement->execute($values);
                if(!$statement->rowCount()) {
                    $check=$iBacolorConnection->prepare('SELECT 1 FROM services WHERE service_id=:id');
                    $check->execute(['id'=>$id]);
                    if(!$check->fetchColumn()) {
                        $iBacolorConnection->rollBack();
                        apiResponse(404,['error'=>'Service not found.']);
                    }
                }
                $serviceId=$id;
                $deleteRequirements=$iBacolorConnection->prepare('DELETE FROM service_requirements WHERE service_id=:id');
                $deleteRequirements->execute(['id'=>$serviceId]);
            } else {
                $statement=$iBacolorConnection->prepare('INSERT INTO services(code,name,description,category,office,status,fee_information,processing_info,instructions) VALUES(:code,:name,:description,:category,:office,:status,:fee,:processing,:instructions)');
                $statement->execute($values);
                $serviceId=(int)$iBacolorConnection->lastInsertId();
            }
            $addRequirement=$iBacolorConnection->prepare('INSERT INTO service_requirements(service_id,requirement,display_order) VALUES(:service_id,:requirement,:display_order)');
            foreach($requirements as $order=>$requirement) {
                $addRequirement->execute(['service_id'=>$serviceId,'requirement'=>$requirement,'display_order'=>$order]);
            }
            $iBacolorConnection->commit();
        } catch(Throwable $exception) {
            if($iBacolorConnection->inTransaction()) $iBacolorConnection->rollBack();
            throw $exception;
        }
        apiResponse(200,['message'=>'Service saved.']);
    }
    if ($action === 'admin.archive-service' && $method === 'POST') {
        $input=apiInput();
        $id=filter_var($input['serviceId'] ?? null,FILTER_VALIDATE_INT);
        if(!$id) apiResponse(422,['error'=>'Choose a valid service.']);
        $statement=$iBacolorConnection->prepare("UPDATE services SET status='Unavailable' WHERE service_id=:id");
        $statement->execute(['id'=>$id]);
        apiResponse(200,['message'=>'Service set to unavailable.']);
    }
    if ($action === 'admin.contacts') {
        $rows=$iBacolorConnection->query(
            'SELECT contact_id,name,type,telephone,email,address,is_verified,contact_name,purpose,category,social_link,fb_page_link,website_link,official_socmed
             FROM public_contacts ORDER BY name'
        )->fetchAll();
        apiResponse(200,['contacts'=>$rows]);
    }
    if ($action === 'admin.save-contact' && $method === 'POST') {
        $input=apiInput();
        $id=filter_var($input['contactId'] ?? null,FILTER_VALIDATE_INT) ?: null;
        $name=apiText($input['name'] ?? '',256);
        $type=apiText($input['type'] ?? '',30);
        $telephone=apiText($input['telephone'] ?? '',101) ?: null;
        $email=apiText($input['email'] ?? '',256) ?: null;
        $address=apiText($input['address'] ?? '',256) ?: null;
        $contactName=apiText($input['contact_name'] ?? '',256) ?: null;
        $purpose=apiText($input['purpose'] ?? '',2000) ?: null;
        $category=apiText($input['category'] ?? '',256) ?: null;
        $socialLink=apiText($input['social_link'] ?? '',256) ?: null;
        $facebook=apiText($input['fb_page_link'] ?? '',256) ?: null;
        $website=apiText($input['website_link'] ?? '',256) ?: null;
        $officialSocial=apiText($input['official_socmed'] ?? '',256) ?: null;
        $isVerified=filter_var($input['is_verified'] ?? false,FILTER_VALIDATE_BOOLEAN);
        if($name==='' || mb_strlen($name)>255 || mb_strlen((string)$telephone)>100 || mb_strlen((string)$email)>255
            || mb_strlen((string)$address)>255 || mb_strlen((string)$contactName)>255 || mb_strlen((string)$category)>255
            || mb_strlen((string)$socialLink)>255 || mb_strlen((string)$facebook)>255 || mb_strlen((string)$website)>255
            || mb_strlen((string)$officialSocial)>255 || !in_array($type,['office','emergency','hotline'],true)
            || ($email!==null && !filter_var($email,FILTER_VALIDATE_EMAIL))) {
            apiResponse(422,['error'=>'Check contact fields and their maximum lengths; link URLs must use HTTPS.']);
        }
        foreach([$socialLink,$facebook,$website,$officialSocial] as $url) {
            if($url!==null && (filter_var($url,FILTER_VALIDATE_URL)===false || strtolower((string)parse_url($url,PHP_URL_SCHEME))!=='https')) {
                apiResponse(422,['error'=>'Contact website links must be valid HTTPS URLs.']);
            }
        }
        $contactValues=[
            'name'=>$name,'type'=>$type,'telephone'=>$telephone,'email'=>$email,'address'=>$address,'contact_name'=>$contactName,
            'purpose'=>$purpose,'category'=>$category,'social_link'=>$socialLink,'fb_page_link'=>$facebook,
            'website_link'=>$website,'official_socmed'=>$officialSocial,'is_verified'=>$isVerified ? 1 : 0,
        ];
        if($id) {
            $statement=$iBacolorConnection->prepare('UPDATE public_contacts SET name=:name,type=:type,telephone=:telephone,email=:email,address=:address,contact_name=:contact_name,purpose=:purpose,category=:category,social_link=:social_link,fb_page_link=:fb_page_link,website_link=:website_link,official_socmed=:official_socmed,is_verified=:is_verified WHERE contact_id=:id');
            $statement->execute($contactValues+['id'=>$id]);
        } else {
            $statement=$iBacolorConnection->prepare('INSERT INTO public_contacts(name,type,telephone,email,address,contact_name,purpose,category,social_link,fb_page_link,website_link,official_socmed,is_verified) VALUES(:name,:type,:telephone,:email,:address,:contact_name,:purpose,:category,:social_link,:fb_page_link,:website_link,:official_socmed,:is_verified)');
            $statement->execute($contactValues);
        }
        apiResponse(200,['message'=>'Contact saved.']);
    }
    if ($action === 'admin.delete-contact' && $method === 'POST') {
        $input=apiInput();
        $id=filter_var($input['contactId'] ?? null,FILTER_VALIDATE_INT);
        if(!$id) apiResponse(422,['error'=>'Choose a valid contact.']);
        $statement=$iBacolorConnection->prepare('DELETE FROM public_contacts WHERE contact_id=:id');
        $statement->execute(['id'=>$id]);
        apiResponse(200,['message'=>'Contact deleted.']);
    }

    apiResponse(404,['error'=>'Unknown action.']);
}

try {
    apiDispatch();
} catch (Throwable $exception) {
    error_log('iBacolor API: '.$exception->getMessage());
    apiResponse(500,['error'=>'The request could not be completed. Please try again.']);
}
