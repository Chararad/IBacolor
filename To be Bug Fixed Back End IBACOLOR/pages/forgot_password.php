<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f7f9">
    <title>Forgot Password | iBacolor</title>
    <link rel="stylesheet" href="../css/pages/register.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f6f7f9;
        }

        .recovery-request-header {
            padding: 16px 24px;
            background: var(--maroon, #651d32);
            box-shadow: 0 3px 12px rgba(31, 12, 16, 0.16);
        }

        .recovery-request-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
        }

        .recovery-request-brand img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 50%;
        }

        .recovery-request-brand strong,
        .recovery-request-brand small {
            display: block;
        }

        .recovery-request-brand strong {
            font-size: 16px;
        }

        .recovery-request-brand small {
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            font-weight: 400;
        }

        .recovery-request-main {
            display: grid;
            flex: 1;
            min-width: 0;
            place-items: center;
            padding: 48px 18px;
        }

        .recovery-request-card {
            width: min(100%, 500px);
            min-width: 0;
            max-width: 100%;
            padding: 38px;
            border: 1px solid var(--line, #e5e0dc);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 14px 40px rgba(31, 12, 16, 0.09);
            animation: recovery-request-enter 260ms ease-out both;
        }

        .recovery-request-icon {
            display: grid;
            width: 68px;
            height: 68px;
            margin: 0 auto 20px;
            place-items: center;
            border-radius: 50%;
            background: rgba(101, 29, 50, 0.08);
            color: var(--maroon, #651d32);
        }

        .recovery-request-icon svg {
            width: 34px;
            height: 34px;
        }

        .recovery-request-card h1 {
            margin: 0;
            color: var(--ink, #282328);
            font-size: clamp(24px, 5vw, 30px);
            font-weight: 800;
            text-align: center;
        }

        .recovery-request-subtitle {
            max-width: 390px;
            margin: 12px auto 28px;
            color: var(--muted, #716a66);
            font-size: 14px;
            line-height: 1.6;
            text-align: center;
        }

        .recovery-request-card label {
            display: block;
            margin-bottom: 8px;
            color: var(--ink, #282328);
            font-size: 13px;
            font-weight: 700;
        }

        .recovery-request-email {
            display: block;
            width: 100%;
            min-height: 48px;
            margin: 0;
            padding: 12px 14px;
            border: 1px solid #cbd3d9;
            border-radius: 7px;
            background: #fff;
            color: var(--ink, #282328);
            font: inherit;
            font-size: 15px;
            transition: border-color 150ms ease;
        }

        .recovery-request-email:hover {
            border-color: var(--red, #8b1e2d);
        }

        .recovery-request-email:focus {
            border-color: var(--red, #8b1e2d);
            outline: 0;
        }

        .recovery-request-actions {
            margin-top: 22px;
        }

        .recovery-request-actions .button {
            width: 100%;
            min-height: 48px;
            transition: transform 150ms ease, box-shadow 150ms ease;
        }

        .recovery-request-actions .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(101, 29, 50, 0.16);
        }

        .recovery-request-note,
        .recovery-request-message {
            margin: 16px 0 0;
            color: var(--muted, #716a66);
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
        }

        .recovery-request-message {
            display: none;
            padding: 11px 13px;
            border: 1px solid #c9d9ed;
            border-radius: 7px;
            background: #f3f8fd;
            color: #28527a;
        }

        .recovery-request-message.is-visible {
            display: block;
        }

        .recovery-request-login {
            display: inline-block;
            margin-top: 22px;
            color: var(--red, #8b1e2d);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .recovery-request-login:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .recovery-request-login:focus-visible {
            border-radius: 3px;
            outline: 2px solid var(--red, #8b1e2d);
            outline-offset: 3px;
        }

        .recovery-request-back {
            text-align: center;
        }

        @keyframes recovery-request-enter {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.985);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 575px) {
            .recovery-request-header {
                padding: 13px 16px;
            }

            .recovery-request-main {
                padding: 28px 14px;
            }

            .recovery-request-card {
                padding: 30px 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <header class="recovery-request-header">
        <a class="recovery-request-brand" href="../main.php" aria-label="iBacolor home">
            <img src="../assets/bacolor-seal.webp" alt="">
            <span>
                <strong>iBacolor</strong>
                <small>Municipal Public Service Portal</small>
            </span>
        </a>
    </header>

    <main class="recovery-request-main">
        <section class="recovery-request-card" aria-labelledby="recovery-request-title">
            <div class="recovery-request-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                    <path d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                    <path d="M12 14v3"></path>
                </svg>
            </div>
            <h1 id="recovery-request-title">Forgot Password</h1>
            <p class="recovery-request-subtitle">Enter your registered email address to receive a verification code.</p>

            <form id="recovery-request-form" novalidate>
                <label for="recovery-email">Email address</label>
                <input class="recovery-request-email" id="recovery-email" type="email" name="email" autocomplete="email" autocapitalize="none" maxlength="100" placeholder="name@example.com" required>
                <div class="recovery-request-actions">
                    <button class="button" id="send-recovery-otp" type="button">Send OTP</button>
                </div>
            </form>
            <p class="recovery-request-note">Email delivery is not connected yet. No reset code will be sent.</p>
            <p class="recovery-request-message" id="recovery-request-message" role="status" aria-live="polite"></p>
            <div class="recovery-request-back">
                <a class="recovery-request-login" href="login.php">Back to Login</a>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const emailInput = document.getElementById("recovery-email");
            const message = document.getElementById("recovery-request-message");

            document.getElementById("recovery-request-form").addEventListener("submit", (event) => {
                event.preventDefault();
            });

            document.getElementById("send-recovery-otp").addEventListener("click", () => {
                if (!emailInput.reportValidity()) return;

                message.textContent = "This is a design preview. Email delivery is not configured, so no verification code was sent.";
                message.classList.add("is-visible");
            });
        })();
    </script>
</body>
</html>
