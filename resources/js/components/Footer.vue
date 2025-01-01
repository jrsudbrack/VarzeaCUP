<template>
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-left">
          <p>&copy; 2024 VarzeaCUP. Todos os direitos reservados.</p>
        </div>
        <div class="footer-right">
          <ul>
            <li><a href="/sobre-nos">Sobre Nós</a></li>
            <li><a href="/contato">Contato</a></li>
            <li><a href="/politica-privacidade">Política de Privacidade</a></li>
            <li><a href="/termos-uso">Termos de Uso</a></li>
          </ul>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer'
  }
  </script>
  
  <style scoped>
  /* Estilos para o Rodapé */
  .footer {
    background-color: #34495e; /* Cor semelhante ao menu */
    color: #fff;
    padding: 20px 0;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 100;
  }
  
  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 100%; /* Remover margens laterais */
    padding: 0 20px;
  }
  
  .footer-left p {
    font-size: 14px;
    color: #f39c12; /* Cor dourada suave */
  }
  
  .footer-right ul {
    list-style: none;
    display: flex;
    justify-content: flex-end;
    margin: 0;
    padding: 0;
  }
  
  .footer-right li {
    margin-left: 20px;
  }
  
  .footer-right a {
    text-decoration: none;
    color: #f1f1f1;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease-in-out;
  }
  
  .footer-right a:hover {
    color: #34495e;
    background-color: #f39c12;
    padding: 5px 10px;
    border-radius: 5px;
  }
  
  @media (max-width: 768px) {
    .footer-content {
      flex-direction: column;
      text-align: center;
    }
  
    .footer-left {
      margin-bottom: 15px;
    }
  
    .footer-right ul {
      flex-direction: column;
      align-items: center;
    }
  
    .footer-right li {
      margin-left: 0;
      margin-top: 10px;
    }
  }
  </style>
  