<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f7f9">
    <title>Verify Your Email | iBacolor</title>
    <link rel="stylesheet" href="../css/pages/register.css">
    <style>
        .registration-otp-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--soft, #f6f7f9);
        }

        .otp-brand-header {
            padding: 16px 24px;
            background: var(--maroon, #651d32);
            box-shadow: 0 3px 12px rgba(31, 12, 16, 0.16);
        }

        .otp-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
        }

        .otp-brand img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 50%;
        }

        .otp-brand strong,
        .otp-brand small {
            display: block;
        }

        .otp-brand strong {
            font-size: 16px;
        }

        .otp-brand small {
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            font-weight: 400;
        }

        .registration-otp-main {
            display: grid;
            flex: 1;
            place-items: center;
            min-width: 0;
            padding: 48px 18px;
        }

        .registration-otp-card {
            width: min(100%, 520px);
            min-width: 0;
            max-width: 100%;
            padding: 38px;
            border: 1px solid var(--line, #e5e0dc);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 14px 40px rgba(31, 12, 16, 0.09);
            text-align: center;
            animation: otp-card-enter 260ms ease-out both;
        }

        .otp-email-icon {
            display: grid;
            width: 68px;
            height: 68px;
            margin: 0 auto 20px;
            place-items: center;
            border-radius: 50%;
            background: rgba(101, 29, 50, 0.08);
            color: var(--maroon, #651d32);
        }

        .otp-email-icon svg {
            width: 34px;
            height: 34px;
        }

        .registration-otp-card h1 {
            margin: 0;
            color: var(--ink, #282328);
            font-size: clamp(24px, 5vw, 30px);
            font-weight: 800;
        }

        .otp-subtitle {
            max-width: 380px;
            margin: 12px auto 0;
            color: var(--muted, #716a66);
            font-size: 14px;
            line-height: 1.6;
        }

        .otp-input-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0 20px;
        }

        .otp-digit {
            width: 54px;
            height: 60px;
            margin: 0;
            padding: 0;
            border: 1px solid #cbd3d9;
            border-radius: 8px;
            background: #fff;
            color: var(--ink, #282328);
            font-size: 25px;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            transition: border-color 150ms ease, box-shadow 150ms ease, transform 150ms ease;
        }

        .otp-digit:hover {
            border-color: var(--red, #8b1e2d);
        }

        .otp-digit:focus {
            border-color: var(--red, #8b1e2d);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(139, 30, 45, 0.13);
            transform: translateY(-2px);
        }

        .otp-digit.has-value {
            border-color: #198754;
            background: #f7fcf9;
        }

        .otp-countdown {
            min-height: 24px;
            margin: 0;
            color: var(--muted, #716a66);
            font-size: 13px;
        }

        .otp-resend {
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--red, #8b1e2d);
            font: inherit;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .otp-resend:disabled {
            color: #8c939a;
            cursor: not-allowed;
            text-decoration: none;
        }

        .otp-actions {
            display: grid;
            gap: 12px;
            margin-top: 24px;
        }

        .otp-actions .button {
            min-height: 48px;
            width: 100%;
        }

        .otp-message {
            display: none;
            margin: 18px 0 0;
            padding: 11px 13px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-size: 13px;
            line-height: 1.5;
            text-align: left;
        }

        .otp-message.is-visible {
            display: block;
        }

        .otp-message.is-error {
            border-color: #f0c4c7;
            background: #fff4f4;
            color: #8b1e2d;
        }

        .otp-message.is-info {
            border-color: #c9d9ed;
            background: #f3f8fd;
            color: #28527a;
        }

        @keyframes otp-card-enter {
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
            .otp-brand-header {
                padding: 13px 16px;
            }

            .registration-otp-main {
                padding: 28px 14px;
            }

            .registration-otp-card {
                padding: 30px 18px;
            }

            .otp-input-group {
                gap: clamp(5px, 2.2vw, 9px);
            }

            .otp-digit {
                width: min(13vw, 50px);
                height: 54px;
                font-size: 22px;
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
<body class="registration-otp-page">
    <header class="otp-brand-header">
        <a class="otp-brand" href="../main.php" aria-label="iBacolor home">
            <img src="../assets/bacolor-seal.webp" alt="">
            <span>
                <strong>iBacolor</strong>
                <small>Municipal Public Service Portal</small>
            </span>
        </a>
    </header>

    <main class="registration-otp-main">
        <section class="registration-otp-card" aria-labelledby="verification-title">
            <div class="otp-email-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="m4 7 8 6 8-6"></path>
                </svg>
            </div>
            <h1 id="verification-title">Verify Your Email</h1>
            <p class="otp-subtitle">Enter the verification code sent to your email address.</p>

            <!-- UI-only until email delivery and server-side OTP verification are configured. -->
            <form id="registration-otp-form" novalidate>
                <div class="otp-input-group" role="group" aria-label="Six-digit verification code">
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" pattern="[0-9]" aria-label="Digit 1" autofocus>
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 2">
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 3">
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 4">
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 5">
                    <input class="otp-digit" type="text" name="otp[]" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 6">
                </div>

                <p class="otp-countdown" id="otp-countdown" aria-live="polite">
                    Resend OTP in <span id="otp-timer">01:30</span>
                </p>
                <button class="otp-resend" id="resend-otp" type="button" disabled>Resend OTP</button>

                <div class="otp-actions">
                    <button class="button" type="submit">Verify OTP</button>
                </div>
                <p class="otp-message" id="otp-message" role="status" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const form = document.getElementById("registration-otp-form");
            const inputs = Array.from(form.querySelectorAll(".otp-digit"));
            const message = document.getElementById("otp-message");
            const resendButton = document.getElementById("resend-otp");
            const timer = document.getElementById("otp-timer");
            let secondsRemaining = 90;

            const showMessage = (text, type) => {
                message.textContent = text;
                message.className = `otp-message is-visible is-${type}`;
            };

            const updateTimer = () => {
                const minutes = String(Math.floor(secondsRemaining / 60)).padStart(2, "0");
                const seconds = String(secondsRemaining % 60).padStart(2, "0");
                timer.textContent = `${minutes}:${seconds}`;

                if (secondsRemaining <= 0) {
                    resendButton.disabled = false;
                    document.getElementById("otp-countdown").textContent = "You can request a new code.";
                    return;
                }

                secondsRemaining -= 1;
                window.setTimeout(updateTimer, 1000);
            };

            inputs.forEach((input, index) => {
                input.addEventListener("beforeinput", (event) => {
                    if (event.data && /\D/.test(event.data) && event.inputType.startsWith("insert")) {
                        event.preventDefault();
                    }
                });

                input.addEventListener("input", () => {
                    const digits = input.value.replace(/\D/g, "");
                    input.value = digits.slice(-1);
                    input.classList.toggle("has-value", input.value !== "");
                    message.className = "otp-message";

                    if (input.value && inputs[index + 1]) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (event) => {
                    if (event.key === "Backspace" && input.value === "" && inputs[index - 1]) {
                        inputs[index - 1].focus();
                    } else if (event.key === "ArrowLeft" && inputs[index - 1]) {
                        inputs[index - 1].focus();
                    } else if (event.key === "ArrowRight" && inputs[index + 1]) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("paste", (event) => {
                    const digits = event.clipboardData.getData("text").replace(/\D/g, "").slice(0, 6);
                    if (!digits) return;

                    event.preventDefault();
                    digits.split("").forEach((digit, digitIndex) => {
                        inputs[digitIndex].value = digit;
                        inputs[digitIndex].classList.add("has-value");
                    });
                    inputs[Math.min(digits.length, inputs.length) - 1].focus();
                });
            });

            form.addEventListener("submit", (event) => {
                event.preventDefault();
                const code = inputs.map((input) => input.value).join("");
                if (code.length !== 6) {
                    showMessage("Enter all 6 digits to continue.", "error");
                    inputs.find((input) => input.value === "")?.focus();
                    return;
                }

                // No OTP is checked and no account is created until backend integration is configured.
                showMessage("OTP verification is not configured yet. No code has been checked and no account has been created.", "info");
            });

            resendButton.addEventListener("click", () => {
                // Email resend will be connected when the registration mail service is configured.
                showMessage("Resending a code is not available until email delivery is configured.", "info");
            });

            updateTimer();
            inputs[0].focus();
        })();
    </script>
</body>
</html>
