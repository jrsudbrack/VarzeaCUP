<template>
  <header class="navbar">
    <div class="navbar-logo">
      <h1>VarzeaCUP</h1>
    </div>
    <nav class="navbar-menu">
      <ul>
        <li><a href="/team">Gerenciar Times</a></li>
        <li><a href="/championship">Gerenciar Campeonatos</a></li>
        <li><a href="/match">Gerenciar Partidas</a></li>
        <li><a href="/user">Gerenciar Usuários</a></li>
        <!-- Formulário de logout com CSRF Token -->
        <li>
          <form action="/logout" method="GET">
            <button type="submit" value="submit">Sair</button>
          </form>
        </li>
      </ul>
    </nav>
  </header>
</template>

<script>
export default {
  data() {
    return {

    }
  },
  methods: {
  }
}
</script>

<style scoped>
/* Importando a fonte Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

/* Estilização do Header */
.navbar {
  margin: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #34495e;
  color: #fff;
  padding: 15px 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  font-family: 'Poppins', sans-serif;
}

.navbar-logo h1 {
  font-size: 28px;
  font-weight: 600;
  color: #f39c12; /* Dourado suave */
  letter-spacing: 1px;
}

.navbar-menu ul {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
}

.navbar-menu li {
  margin: 0 20px;
  position: relative;
}

.navbar-menu a,
.navbar-menu button {
  text-decoration: none;
  color: #f1f1f1;
  font-size: 16px;
  font-weight: 500;
  padding: 10px 20px;
  border-radius: 5px;
  transition: all 0.3s ease-in-out;
  display: inline-block;
  border: none;
}

.navbar-menu a:hover,
.navbar-menu button:hover {
  color: #34495e;
  background-color: #f39c12; /* Dourado */
}

.navbar-menu button {
  background-color: #e74c3c; /* Vermelho suave */
  font-weight: 600;
}

.navbar-menu button:hover {
  background-color: #c0392b;
}

@media (max-width: 768px) {
  .navbar {
    padding: 15px 20px;
  }

  .navbar-menu ul {
    flex-direction: column;
    align-items: center;
    width: 100%;
  }

  .navbar-menu li {
    margin: 10px 0;
  }
}
</style>
