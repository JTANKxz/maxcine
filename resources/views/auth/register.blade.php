<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Plataforma de Streaming</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --cor-primaria: #ffc107;
            /* Amarelo vibrante */
            --cor-secundaria: #303030;
            /* Cinza escuro para elementos secundários */
            --cor-fundo: #050505;
            /* Preto quase total para o fundo */
            --cor-fundo-input: #1a1a1a;
            /* Cinza um pouco mais claro para inputs */
            --cor-texto: #f5f5f5;
            /* Branco suave para texto principal */
            --cor-texto-claro: rgba(255, 255, 255, 0.7);
            /* Branco com transparência para placeholders e links */
            --cor-borda: #444;
            /* Cinza para bordas */
            --cor-sombra: rgba(0, 0, 0, 0.5);
            /* Sombra para profundidade */
            --cor-erro: #e74c3c;
            /* Vermelho para mensagens de erro */
            --cor-sucesso: #2ecc71;
            /* Verde para mensagens de sucesso */
        }

        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .signup-container {
            background-color: var(--cor-secundaria);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px var(--cor-sombra);
            width: 100%;
            max-width: 420px;
            /* Um pouco mais largo para mais campos */
            text-align: center;
        }

        .signup-logo {
            font-size: 32px;
            color: var(--cor-primaria);
            margin-bottom: 25px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .input-group {
            margin-bottom: 18px;
            /* Espaçamento ligeiramente menor entre os grupos */
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--cor-texto-claro);
        }

        .input-wrapper {
            position: relative;
        }

        .input-group input[type="text"],
        .input-group input[type="email"],
        .input-group input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            padding-left: 40px;
            /* Espaço para o ícone */
            background-color: var(--cor-fundo-input);
            border: 1px solid var(--cor-borda);
            border-radius: 6px;
            color: var(--cor-texto);
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group input[type="text"]:focus,
        .input-group input[type="email"]:focus,
        .input-group input[type="password"]:focus {
            border-color: var(--cor-primaria);
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.3);
            outline: none;
        }

        .input-group input[type="text"]::placeholder,
        .input-group input[type="email"]::placeholder,
        .input-group input[type="password"]::placeholder {
            color: var(--cor-texto-claro);
            opacity: 0.7;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cor-texto-claro);
            font-size: 16px;
        }

        .btn-signup {
            background-color: var(--cor-primaria);
            color: var(--cor-fundo);
            border: none;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            /* Espaço antes do botão */
        }

        .btn-signup:hover {
            background-color: #ffac00;
            transform: translateY(-2px);
        }

        .login-link-container {
            /* Renomeado para clareza */
            margin-top: 25px;
            font-size: 13px;
        }

        .login-link-container a {
            color: var(--cor-texto-claro);
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link-container a:hover {
            color: var(--cor-primaria);
            text-decoration: underline;
        }

        .message-area {
            /* Para mensagens de erro ou sucesso */
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
            /* Oculto por padrão */
            text-align: left;
        }

        .message-area.error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--cor-erro);
            border: 1px solid var(--cor-erro);
        }

        .message-area.success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--cor-sucesso);
            border: 1px solid var(--cor-sucesso);
        }


        /* Responsividade */
        @media (max-width: 480px) {
            .signup-container {
                padding: 25px 20px;
            }

            .signup-logo {
                font-size: 28px;
                margin-bottom: 20px;
            }

            .input-group input[type="text"],
            .input-group input[type="email"],
            .input-group input[type="password"] {
                padding: 10px 12px;
                padding-left: 35px;
                font-size: 15px;
            }

            .input-icon {
                left: 10px;
                font-size: 14px;
            }

            .btn-signup {
                padding: 12px 18px;
                font-size: 15px;
            }

            .login-link-container {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <div class="signup-logo">MASTERCINE</div>
        <x-alert />
        <form id="signupForm" method="POST" action="{{ route('auth.register') }}">
            @csrf
            <div class="input-group">
                <label for="name">Nome de usuário</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="name" name="name" placeholder="john doe" required>
                </div>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
                </div>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Crie uma senha forte" required>
                </div>
            </div>
            <button type="submit" class="btn-signup">Criar Conta</button>
        </form>
        <div class="login-link-container">
            <p>Já tem uma conta? <a href="{{ route('auth.login') }}">Faça login</a></p>
        </div>
    </div>
</body>

</html>