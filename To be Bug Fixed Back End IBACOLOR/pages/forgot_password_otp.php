<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f7f9">
    <title>Password Recovery Verification | iBacolor</title>
    <link rel="stylesheet" href="../css/pages/register.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f6f7f9;
        }

        .recovery-otp-header {
            padding: 16px 24px;
            background: var(--maroon, #651d32);
            box-shadow: 0 3px 12px rgba(31, 12, 16, 0.16);
        }

        .recovery-otp-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
        }

        .recovery-otp-brand img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 50%;
        }

        .recovery-otp-brand strong,
        .recovery-otp-brand small {
            display: block;
        }

        .recovery-otp-brand strong {
            font-size: 16px;
        }

        .recovery-otp-brand small {
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            font-weight: 400;
        }

        .recovery-otp-main {
            display: grid;
            flex: 1;
            min-width: 0;
            place-items: center;
            padding: 48px 18px;
        }

        .recovery-otp-card {
            width: min(100%, 520px);
            min-width: 0;
            max-width: 100%;
            padding: 38px;
            border: 1px solid var(--line, #e5e0dc);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 14px 40px rgba(31, 12, 16, 0.09);
            text-align: center;
            animation: recovery-card-enter 260ms ease-out both;
        }

        .recovery-otp-icon {
            display: grid;
            width: 68px;
            height: 68px;
            margin: 0 auto 20px;
            place-items: center;
            border-radius: 50%;
            background: rgba(101, 29, 50, 0.08);
            color: var(--maroon, #651d32);
        }

        .recovery-otp-icon svg {
            width: 34px;
            height: 34px;
        }

        .recovery-otp-card h1 {
            margin: 0;
            color: var(--ink, #282328);
            font-size: clamp(23px, 5vw, 29px);
            font-weight: 800;
        }

        .recovery-otp-subtitle {
            max-width: 380px;
            margin: 12px auto 0;
            color: var(--muted, #716a66);
            font-size: 14px;
            line-height: 1.6;
        }

        .recovery-otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0 20px;
        }

        .recovery-otp-digit {
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

        .recovery-otp-digit:hover {
            border-color: var(--red, #8b1e2d);
        }

        .recovery-otp-digit:focus {
            border-color: var(--red, #8b1e2d);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(139, 30, 45, 0.13);
            transform: translateY(-2px);
        }

        .recovery-otp-digit.has-value {
            border-color: #198754;
            background: #f7fcf9;
        }

        .recovery-otp-countdown {
            min-height: 24px;
            margin: 0;
            color: var(--muted, #716a66);
            font-size: 13px;
        }

        .recovery-otp-resend {
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--red, #8b1e2d);
            font: inherit;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .recovery-otp-resend:disabled {
            color: #8c939a;
            cursor: not-allowed;
            text-decoration: none;
        }

        .recovery-otp-actions {
            margin-top: 24px;
        }

        .recovery-otp-actions .button {
            width: 100%;
            min-height: 48px;
        }

        .recovery-otp-message {
            display: none;
            margin: 18px 0 0;
            padding: 11px 13px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-size: 13px;
            line-height: 1.5;
            text-align: left;
        }

        .recovery-otp-message.is-visible {
            display: block;
        }

        .recovery-otp-message.is-error {
            border-color: #f0c4c7;
            background: #fff4f4;
            color: #8b1e2d;
        }

        .recovery-otp-message.is-info {
            border-color: #c9d9ed;
            background: #f3f8fd;
            color: #28527a;
        }

        @keyframes recovery-card-enter {
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
            .recovery-otp-header {
                padding: 13px 16px;
            }

            .recovery-otp-main {
                padding: 28px 14px;
            }

            .recovery-otp-card {
                padding: 30px 18px;
            }

            .recovery-otp-inputs {
                gap: clamp(5px, 2.2vw, 9px);
            }

            .recovery-otp-digit {
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
<body>
    <header class="recovery-otp-header">
        <a class="recovery-otp-brand" href="../main.php" aria-label="iBacolor home">
            <img src="../assets/bacolor-seal.webp" alt="">
            <span>
                <strong>iBacolor</strong>
                <small>Municipal Public Service Portal</small>
            </span>
        </a>
    </header>

    <main class="recovery-otp-main">
        <section class="recovery-otp-card" aria-labelledby="recovery-otp-title">
            <div class="recovery-otp-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 20 6v5c0 5-3.4 8.2-8 10-4.6-1.8-8-5-8-10V6z"></path>
                    <circle cx="12" cy="11" r="2"></circle>
                    <path d="M12 13v3"></path>
                </svg>
            </div>
            <h1 id="recovery-otp-title">Password Recovery Verification</h1>
            <p class="recovery-otp-subtitle">Enter the 6-digit verification code sent to your email.</p>

            <!-- UI-only until the password recovery email service and backend are configured. -->
            <div class="recovery-otp-inputs" role="group" aria-label="Six-digit verification code">
                <input class="recovery-otp-digit" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" pattern="[0-9]" aria-label="Digit 1" autofocus>
                <input class="recovery-otp-digit" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 2">
                <input class="recovery-otp-digit" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 3">
                <input class="recovery-otp-digit" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 4">
                <input class="recovery-otp-digit" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 5">
                <input class="recovery-otp-digit" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Digit 6">
            </div>

            <p class="recovery-otp-countdown" id="recovery-otp-countdown" aria-live="polite">
                Resend OTP in <span id="recovery-otp-timer">01:30</span>
            </p>
            <button class="recovery-otp-resend" id="recovery-otp-resend" type="button" disabled>Resend OTP</button>

            <div class="recovery-otp-actions">
                <button class="button" id="verify-recovery-otp" type="button">Verify OTP</button>
            </div>
            <p class="recovery-otp-message" id="recovery-otp-message" role="status" aria-live="polite"></p>
        </section>
    </main>

    <script>
        (() => {
            const inputs = Array.from(document.querySelectorAll(".recovery-otp-digit"));
            const message = document.getElementById("recovery-otp-message");
            const resendButton = document.getElementById("recovery-otp-resend");
            const countdown = document.getElementById("recovery-otp-countdown");
            const timer = document.getElementById("recovery-otp-timer");
            let secondsRemaining = 90;

            const showMessage = (text, type) => {
                message.textContent = text;
                message.className = `recovery-otp-message is-visible is-${type}`;
            };

            const updateTimer = () => {
                const minutes = String(Math.floor(secondsRemaining / 60)).padStart(2, "0");
                const seconds = String(secondsRemaining % 60).padStart(2, "0");
                timer.textContent = `${minutes}:${seconds}`;

                if (secondsRemaining <= 0) {
                    resendButton.disabled = false;
                    countdown.textContent = "You can request a new code.";
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
                    message.className = "recovery-otp-message";

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

            document.getElementById("verify-recovery-otp").addEventListener("click", () => {
                const code = inputs.map((input) => input.value).join("");
                if (code.length !== 6) {
                    showMessage("Enter all 6 digits to continue.", "error");
                    inputs.find((input) => input.value === "")?.focus();
                    return;
                }

                // UI-only: no code is checked and no password reset page is started.
                showMessage("OTP verification is not configured yet. No code has been checked and no password reset has started.", "info");
            });

            resendButton.addEventListener("click", () => {
                // Resending will be connected when password recovery email delivery is configured.
                showMessage("Resending a code is not available until email delivery is configured.", "info");
            });

            updateTimer();
            inputs[0].focus();
        })();
    </script>
</body>
</html>
