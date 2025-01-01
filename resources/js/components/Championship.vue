<template>
    <div class="container">
      <!-- Formulário para adicionar ou editar campeonato -->
      <form @submit.prevent="handleSubmit" class="form">
        <div class="form-group">
          <input
            v-model="form.name"
            type="text"
            placeholder="Nome do campeonato"
            class="input"
            required
          />
        </div>
        <div class="form-group">
          <input
            v-model="form.year"
            type="number"
            placeholder="Ano"
            class="input"
            required
          />
        </div>
        <button type="submit" class="btn-submit">
          {{ form.id ? 'Atualizar' : 'Adicionar' }} Campeonato
        </button>
        <!-- Botão de cancelar atualização -->
        <button
          v-if="form.id"
          @click="cancelUpdate"
          type="button"
          class="btn-cancel"
        >
          Cancelar
        </button>
      </form>
  
      <!-- Lista de campeonatos -->
      <ul class="championship-list">
        <li v-for="championship in championships" :key="championship.id" class="championship-item">
          <div>
            <h2 class="championship-name">{{ championship.name }}</h2>
            <p class="championship-year">{{ championship.year }}</p>
          </div>
          <div class="championship-actions">
            <button @click="editChampionship(championship)" class="btn-action">Editar</button>
            <button @click="deleteChampionship(championship.id)" class="btn-action">Excluir</button>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, reactive, onMounted } from 'vue';
  
  export default {
    name: 'ChampionshipManager',
    setup() {
      const apiUrl = import.meta.env.VITE_URL_API;
      const authToken = sessionStorage.getItem('auth_token');
  
      const championships = ref([]);
      const form = reactive({ id: null, name: '', year: '' });
  
      const axiosInstance = axios.create({
        baseURL: apiUrl,
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      });
  
      // Fetch initial data
      const fetchChampionships = async () => {
        try {
          const { data } = await axiosInstance.get('/championships');
          championships.value = data;
        } catch (error) {
          console.error('Erro ao buscar campeonatos:', error);
        }
      };
  
      // Add or update championship
      const handleSubmit = async () => {
        try {
          if (form.id) {
            await axiosInstance.put(`/championships/${form.id}`, form);
            alert('Campeonato atualizado com sucesso!');
          } else {
            await axiosInstance.post('/championships', form);
            alert('Campeonato adicionado com sucesso!');
          }
          form.id = null;
          form.name = '';
          form.year = '';
          fetchChampionships();
        } catch (error) {
          console.error('Erro ao salvar campeonato:', error);
        }
      };
  
      // Edit championship
      const editChampionship = (championship) => {
        form.id = championship.id;
        form.name = championship.name;
        form.year = championship.year;
      };
  
      // Cancel update
      const cancelUpdate = () => {
        form.id = null;
        form.name = '';
        form.year = '';
      };
  
      // Delete championship
      const deleteChampionship = async (id) => {
        if (!confirm('Deseja realmente excluir este campeonato?')) return;
        try {
          await axiosInstance.delete(`/championships/${id}`);
          alert('Campeonato excluído com sucesso!');
          fetchChampionships();
        } catch (error) {
          console.error('Erro ao excluir campeonato:', error);
        }
      };
  
      onMounted(fetchChampionships);
  
      return {
        championships,
        form,
        fetchChampionships,
        handleSubmit,
        editChampionship,
        cancelUpdate,
        deleteChampionship,
      };
    },
  };
  </script>
  
  <style scoped>
  /* Estilos gerais */
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
  
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
  }
  
  .container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  
  .title {
    text-align: center;
    font-size: 2rem;
    font-weight: 500;
    color: #333;
    margin-bottom: 2rem;
  }
  
  /* Estilos do formulário */
  .form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
  }
  
  .input {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s;
  }
  
  .input:focus {
    border-color: #4a90e2;
  }
  
  .btn-submit {
    padding: 0.8rem;
    background-color: #4a90e2;
    color: white;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-submit:hover {
    background-color: #357abd;
  }
  
  /* Estilos do botão de cancelar */
  .btn-cancel {
    padding: 0.8rem;
    background-color: #e0e0e0;
    color: #333;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-cancel:hover {
    background-color: #ccc;
  }
  
  /* Estilos da lista de campeonatos */
  .championship-list {
    list-style-type: none;
    padding: 0;
    margin-top: 2rem;
  }
  
  .championship-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    margin-bottom: 1rem;
  }
  
  .championship-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
  }
  
  .championship-year {
    color: #777;
  }
  
  .championship-actions {
    display: flex;
    gap: 1rem;
  }
  
  .btn-action {
    padding: 0.6rem 1.2rem;
    background-color: #f0f0f0;
    color: #333;
    font-size: 0.9rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-action:hover {
    background-color: #e0e0e0;
  }
  </style>
  