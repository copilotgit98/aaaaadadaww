<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <title>Escolha seu Plano - GreenCash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Montserrat:wght@700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
    <style>
        /* --- FUNDO PREMIUM, ANIMADO, MODERNO --- */
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', 'Montserrat', Arial, sans-serif;
            overflow-x: hidden;
            background: linear-gradient(120deg, #0f2027 0%, #2c5364 100%);
            position: relative;
        }
        /* Fundo animado com blobs */
        .bg-blobs {
            position: fixed;
            width: 100vw; height: 100vh; z-index: 0;
            overflow: hidden; pointer-events: none;
            top: 0; left: 0;
        }
        .bg-blob {
            position: absolute;
            border-radius: 50%;
            opacity: .33;
            filter: blur(70px) saturate(1.3) brightness(1.1);
            animation: blobmove 18s infinite alternate cubic-bezier(.7,0,.33,1);
            z-index: 0;
        }
        .bg-blob1 { width: 700px; height: 700px; background: #43e97b; top: -220px; left: -180px; animation-delay: 0s;}
        .bg-blob2 { width: 750px; height: 750px; background: #38f9d7; bottom: -210px; right: -220px; animation-delay: 3s;}
        .bg-blob3 { width: 400px; height: 400px; background: #1976d2; top: 22vh; right: -180px; animation-delay: 7s; opacity: .23;}
        .bg-blob4 { width: 380px; height: 500px; background: #0f2027; bottom: -120px; left: 60px; animation-delay: 11s; opacity: .23;}
        @keyframes blobmove {
            0% { transform: scale(1) translateY(0) rotate(0deg);}
            100% { transform: scale(1.16) translateY(60px) rotate(22deg);}
        }

        /* --- CONTAINER CENTRAL DOS PLANOS --- */
        .planos-container {
            max-width: 520px;
            margin: 78px auto 0 auto;
            text-align: center;
            background: rgba(255,255,255,0.98);
            border-radius: 35px 35px 24px 24px;
            box-shadow: 0 22px 88px -10px #085e7e3c, 0 5px 22px #16d46312;
            padding: 0 44px 40px 44px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px) saturate(1.12);
            border: 2.5px solid #43e97b33;
            animation: fadeInUp .9s cubic-bezier(.42,0,.24,1);
        }
        .brand-bar {
            display: flex; align-items: center; justify-content: center;
            padding: 38px 0 8px 0;
            gap: 13px;
        }
        .brand-bar .material-symbols-rounded {
            font-size: 2.5em; color: #16d463; filter: drop-shadow(0 2px 12px #43e97b60);
        }
        .brand-title {
            font-family: 'Montserrat', 'Inter', sans-serif;
            font-weight: 900;
            font-size: 2.05em;
            letter-spacing: .02em;
            background: linear-gradient(90deg,#1976d2 40%,#43e97b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 16px #1976d240;
        }
        .brand-desc {
            font-size: 1.13em;
            color: #0f2027bb;
            margin-top: 2px;
            font-weight: 600;
            letter-spacing: .01em;
        }
        .planos-container h2 {
            font-family: 'Montserrat', 'Inter', sans-serif;
            font-weight: 900;
            font-size: 2.18em;
            margin: 36px 0 26px 0;
            color: #1976d2;
            letter-spacing: 0.14em;
            text-shadow: 0 2px 15px #1976d22a;
        }
        /* --- CARDS DOS PLANOS --- */
        .plano-lista {
            display: flex; flex-direction: column; gap: 24px; margin-bottom: 18px;
        }
        .plano-card {
            border: 2.8px solid #d3f7e1;
            border-radius: 20px;
            padding: 30px 22px 26px 22px;
            background: linear-gradient(120deg, #f7f7fa 60%, #e7f5f3 100%);
            cursor: pointer;
            transition: 
                box-shadow .22s, border-color .18s, background .28s, 
                transform .16s cubic-bezier(.42,0,.58,1);
            box-shadow: 0 2px 16px #16d46311, 0 1.5px 10px #1976d222;
            position: relative;
            z-index: 1;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            outline: none;
        }
        .plano-card.selected, .plano-card:focus-visible, .plano-card:hover {
            border-color: #1976d2;
            background: linear-gradient(110deg, #e3f5e8 90%, #d7e8fc 100%);
            box-shadow: 0 10px 32px #1976d239, 0 1.5px 22px #16d4631e;
            transform: scale(1.035) translateY(-3px);
        }
        .plano-card.selected::after, .plano-card:hover::after {
            content: ""; display: block; position: absolute;
            top: -9px; left: 50%; transform: translateX(-50%);
            width: 72%; height: 8px;
            background: linear-gradient(90deg, #43e97b 0%, #1976d2 120%);
            opacity: .19;
            border-radius: 10px;
        }
        .plano-icone {
            font-size: 2.8em;
            color: #16d463;
            background: #e3f9eb;
            border-radius: 50%;
            padding: 13px;
            margin-right: 2px;
            box-shadow: 0 2px 8px #16d46318;
            flex-shrink: 0;
            transition: background .2s, color .2s;
        }
        .plano-card.selected .plano-icone, 
        .plano-card:focus-visible .plano-icone, 
        .plano-card:hover .plano-icone {
            color: #1976d2;
            background: #e6f6fd;
        }
        .plano-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .plano-titulo {
            font-size: 1.28em;
            font-weight: 900;
            color: #1976d2;
            margin-bottom: 4px;
            letter-spacing: 0.1px;
            text-shadow: 0 2px 10px #1976d230;
            font-family: 'Montserrat', 'Inter', sans-serif;
        }
        .plano-preco {
            font-size: 1.16em;
            font-weight: bold;
            color: #16d463;
            margin-bottom: 7px;
            letter-spacing: .11px;
            filter: brightness(1.09);
            text-shadow: 0 2px 7px #16d46322;
            font-family: 'Montserrat', 'Inter', sans-serif;
        }
        .plano-desc {
            font-size: 1.07em;
            color: #3b4151;
            opacity: 0.94;
            font-weight: 600;
            letter-spacing: 0.09px;
        }
        /* Ícones diferenciados para cada plano */
        .plano-card[data-plano="basico"] .plano-icone::before { content: "account_balance_wallet"; }
        .plano-card[data-plano="intermediario"] .plano-icone::before { content: "trending_up"; }
        .plano-card[data-plano="avancado"] .plano-icone::before { content: "auto_awesome"; }

        /* --- FORMULÁRIO DO CARTÃO --- */
        #paymentSection {
            display: none;
            margin-top: 38px;
            background: rgba(255,255,255,0.98);
            border-radius: 25px;
            box-shadow: 0 5px 40px #1976d23a, 0 1.5px 2px #16d46318;
            padding: 38px 24px 30px 24px;
            animation: fadeIn .7s cubic-bezier(.3,1,.6,1);
            z-index: 2;
            position: relative;
            backdrop-filter: blur(10px) saturate(1.12);
            border: 1.5px solid #43e97b22;
        }
        #paymentSection h3 {
            font-weight: 800;
            color: #1976d2;
            margin-bottom: 25px;
            letter-spacing: .015em;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 0 1px 7px #1976d229;
        }
        .mb-3 { margin-bottom: 1.42em; text-align: left; }
        label {
            display: block;
            margin-bottom: 4.5px;
            font-weight: 700;
            color: #1976d2;
            letter-spacing: .01em;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 13px 13px;
            border-radius: 8px;
            border: 1.8px solid #d2e6fc;
            font-size: 1.05em;
            background: #f9fcfa;
            transition: border .2s, box-shadow .2s;
            outline: none;
        }
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            border-color: #43e97b;
            background: #fff;
            box-shadow: 0 0 0 2px #16d46330;
        }
        select.form-select { padding-left: 13px; }
        button, .btn {
            padding: 13px 0;
            font-size: 1.13em;
            font-weight: 900;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #16d463 65%, #1976d2 100%);
            color: #fff;
            box-shadow: 0 2px 18px #16d46335;
            cursor: pointer;
            transition: background .23s, color .22s, box-shadow .22s;
            margin-top: 15px;
            width: 100%;
            letter-spacing: .06em;
            outline: none;
        }
        button:active, .btn:active {
            background: linear-gradient(90deg, #13b856 65%, #388e3c 100%);
            box-shadow: 0 2px 16px #1976d236;
        }
        button:focus-visible {
            outline: 2px solid #1976d2;
            outline-offset: 3px;
        }

        /* --- MODAL SUCESSO --- */
        #modal-sucesso-plano {
            display: none;
            position: fixed; 
            top: 0; left: 0;
            width: 100vw; height: 100vh; z-index: 9999;
            background: rgba(16, 32, 39, 0.28);
            align-items: center; justify-content: center;
            animation: fadeInBg .33s cubic-bezier(.4,0,.2,1);
        }
        #modal-sucesso-plano .modal-content {
            background: #fff;
            padding: 2.7em 2.8em;
            border-radius: 26px;
            box-shadow: 0 6px 70px #16d46336, 0 2px 12px #1976d233;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2em;
            max-width: 96vw;
            animation: modalPop .4s cubic-bezier(.4,0,.2,1);
        }
        #modal-sucesso-plano .modal-content .material-symbols-rounded {
            font-size: 3.42em;
            color: #16d463;
            animation: check-bounce .7s cubic-bezier(.4,0,.2,1);
        }
        #modal-sucesso-plano .modal-content .msg-sucesso {
            font-size: 1.31em;
            font-weight: 800;
            color: #1976d2;
            text-shadow: 0 1px 6px #16d46315;
            margin-bottom: 7px;
            letter-spacing: .03em;
        }
        /* --- RESPONSIVO --- */
        @media (max-width: 800px) {
            .planos-container { max-width: 99vw; padding: 0 3vw 16px 3vw; margin: 19px auto 0 auto;}
            #paymentSection { padding: 14px 2vw; }
            .plano-card { padding: 16px 5px; }
            .brand-bar { font-size: 1.1em; }
        }
        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 7px; background: #d2f1e7; border-radius: 7px; }
        ::-webkit-scrollbar-thumb { background: #16d463b8; border-radius: 7px; }
        ::-webkit-scrollbar-thumb:hover { background: #1976d2cc; }
        /* --- ANIMAÇÕES --- */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(55px);} to { opacity: 1; transform: translateY(0);} }
        @keyframes fadeIn { from { opacity: 0;} to { opacity: 1;} }
        @keyframes fadeInBg { from { background: rgba(0,0,0,0);} to { background: rgba(16,32,39,.28);} }
        @keyframes modalPop { from { transform: scale(.93);} to { transform: scale(1);} }
        @keyframes check-bounce {
            0% { transform: scale(.7);}
            60% { transform: scale(1.16);}
            100% { transform: scale(1);}
        }

         /* Botão voltar */
         .btn-voltar {
            position: absolute;
            top: 28px;
            left: 24px;
            z-index: 10;
            background: rgba(255,255,255,0.98);
            border: 2px solid #1976d2;
            color: #1976d2;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 16px #1976d229;
            transition: background .18s, color .18s, border-color .18s, box-shadow .22s;
            cursor: pointer;
            font-size: 1.95em;
            outline: none;
        }
        .btn-voltar:hover, .btn-voltar:focus-visible {
            background: #1976d2;
            color: #fff;
            border-color: #16d463;
            box-shadow: 0 2px 16px #16d4632b;
        }
        @media (max-width: 600px) {
            .btn-voltar { top: 13px; left: 9px; width: 42px; height: 42px; font-size: 1.36em;}
        }
    </style>
</head>
<body>
        <!-- Botão voltar -->
        <button class="btn-voltar" onclick="window.history.back()" title="Voltar">
    <span class="material-symbols-rounded">arrow_back</span>
</button> 
    <!-- Fundo animado -->
    <div class="bg-blobs">
        <div class="bg-blob bg-blob1"></div>
        <div class="bg-blob bg-blob2"></div>
        <div class="bg-blob bg-blob3"></div>
        <div class="bg-blob bg-blob4"></div>
    </div>

    <div class="planos-container">
        <div class="brand-bar">
            <span class="material-symbols-rounded">account_balance_wallet</span>
            <span class="brand-title">GreenCash</span>
        </div>
        <div class="brand-desc">Escolha o plano perfeito para você controlar suas finanças!</div>
        <h2>Escolha seu Plano</h2>
        <div class="plano-lista">
            <div class="plano-card" data-plano="basico" tabindex="0">
                <span class="material-symbols-rounded plano-icone">account_balance_wallet</span>
                <div class="plano-info">
                    <div class="plano-titulo">Básico</div>
                    <div class="plano-preco">R$0/mês</div>
                    <div class="plano-desc">Controle financeiro essencial<br><span style="color:#43e97b;font-weight:700;">Ideal para começar!</span></div>
                </div>
            </div>
            <div class="plano-card" data-plano="intermediario" tabindex="0">
                <span class="material-symbols-rounded plano-icone">trending_up</span>
                <div class="plano-info">
                    <div class="plano-titulo">Intermediário</div>
                    <div class="plano-preco">R$19,90/mês</div>
                    <div class="plano-desc">Relatórios, gráficos e recursos avançados<br><span style="color:#1976d2;font-weight:700;">Gestão facilitada!</span></div>
                </div>
            </div>
            <div class="plano-card" data-plano="avancado" tabindex="0">
                <span class="material-symbols-rounded plano-icone">auto_awesome</span>
                <div class="plano-info">
                    <div class="plano-titulo">Avançado</div>
                    <div class="plano-preco">R$29,90/mês</div>
                    <div class="plano-desc">Tudo do Intermediário + Suporte<br><span style="color:#fbc02d;font-weight:700;">Para quem quer tudo!</span></div>
                </div>
            </div>
        </div>
        <div id="paymentSection">
            <h3>
                <span class="material-symbols-rounded" style="font-size:1.3em;vertical-align:middle;color:#43e97b;background:#e3f9eb;border-radius:50%;padding:7px;">credit_card</span>
                Adicione seu Cartão
            </h3>
            <form id="addCardForm" style="max-width:340px; margin:0 auto;">
                <input type="hidden" id="selectedPlano" name="plano" value="">
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">Número do Cartão</label>
                    <input type="text" class="form-control" id="cardNumber" name="numero" placeholder="**** **** **** ****" required autocomplete="cc-number">
                </div>
                <div class="mb-3">
                    <label for="cardHolder" class="form-label">Nome do Titular</label>
                    <input type="text" class="form-control" id="cardHolder" name="titular" placeholder="Nome do Titular" required autocomplete="cc-name">
                </div>
                <div class="mb-3">
                    <label for="cardExpiry" class="form-label">Data de Expiração</label>
                    <input type="text" class="form-control" id="cardExpiry" name="validade" placeholder="MM/AA" required autocomplete="cc-exp">
                </div>
                <div class="mb-3">
                    <label for="cardCVV" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cardCVV" name="cvv" placeholder="123" maxlength="4" required autocomplete="cc-csc">
                </div>
                <div class="mb-3">
                    <label for="salary" class="form-label">Salário</label>
                    <input type="number" class="form-control" id="salary" name="salario" placeholder="Ex.: 2000" required min="0">
                </div>
                <div class="mb-3">
                    <label for="creditLimit" class="form-label">Limite do Cartão</label>
                    <input type="number" class="form-control" id="creditLimit" name="limite" placeholder="Ex.: 10000" required min="0">
                </div>
                <div class="mb-3">
                    <label for="cardType" class="form-label">Tipo do Cartão</label>
                    <select class="form-select" id="cardType" name="tipo" required>
                        <option value="Mastercard" selected>MasterCard</option>
                        <option value="Visa">Visa</option>
                        <option value="AmericanExpress">American Express</option>
                        <option value="Discover">Discover</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100" style="font-weight:bold;">SALVAR E ASSINAR PLANO</button>
            </form>
            <div class="msg-sucesso" id="msgSucesso" style="display:none;"></div>
        </div>
    </div>

    <!-- Modal de sucesso -->
    <div id="modal-sucesso-plano">
      <div class="modal-content">
        <span class="material-symbols-rounded">check_circle</span>
        <div class="msg-sucesso">Cadastro realizado com sucesso!</div>
      </div>
    </div>

    <script>
        // Plano selection logic
        let planoEscolhido = null;
        document.querySelectorAll('.plano-card').forEach(card => {
            card.onclick = function() {
                document.querySelectorAll('.plano-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                planoEscolhido = this.getAttribute('data-plano');
                document.getElementById('selectedPlano').value = planoEscolhido;
                document.getElementById('paymentSection').style.display = 'block';
            }
            // Acessibilidade: seleção via teclado
            card.onkeyup = function(e) {
                if (e.key === "Enter" || e.key === " ") { this.click(); }
            }
        });

        // Máscaras
        document.getElementById("cardHolder").addEventListener("input", function (e) {
            this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, "");
        });
        document.getElementById("cardNumber").addEventListener("input", function (e) {
            let val = this.value.replace(/\D/g, "").slice(0, 16);
            let formatted = "";
            for (let i = 0; i < val.length; i += 4) {
                if (i > 0) formatted += " ";
                formatted += val.substr(i, 4);
            }
            this.value = formatted;
        });
        document.getElementById("cardExpiry").addEventListener("input", function (e) {
            let val = this.value.replace(/\D/g, "").slice(0, 4);
            if (val.length > 2) {
                this.value = val.slice(0, 2) + "/" + val.slice(2);
            } else {
                this.value = val;
            }
        });
        document.getElementById("cardCVV").addEventListener("input", function (e) {
            let val = this.value.replace(/\D/g, "");
            this.value = val.slice(0, 3);
        });
        document.getElementById("salary").addEventListener("input", function (e) {
            this.value = this.value.replace(/\D/g, "");
        });
        document.getElementById("creditLimit").addEventListener("input", function (e) {
            this.value = this.value.replace(/\D/g, "");
        });

        // Submit do cartão
        document.getElementById("addCardForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const dados = {
                plano: document.getElementById('selectedPlano').value,
                numero: document.getElementById('cardNumber').value.replace(/\s/g, ''),
                titular: document.getElementById('cardHolder').value,
                validade: document.getElementById('cardExpiry').value,
                cvv: document.getElementById('cardCVV').value,
                salario: document.getElementById('salary').value,
                limite: document.getElementById('creditLimit').value,
                tipo: document.getElementById('cardType').value
            };
            fetch('plano_assinar.php', {
                method: 'POST',
                body: new URLSearchParams(dados)
            }).then(res=>res.json()).then(resp=>{
                if(resp.sucesso){
                    document.getElementById('modal-sucesso-plano').style.display = 'flex';
                    setTimeout(()=>window.location.href='dashboard.php', 1800);
                } else {
                    alert('Erro ao assinar plano');
                }
            });
        });
    </script>
</body>
</html>