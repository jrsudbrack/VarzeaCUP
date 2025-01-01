<template>
  <div class="container">
    <!-- Formulário para adicionar ou editar time -->
    <form @submit.prevent="handleSubmit" class="form">
      <div class="form-group">
        <input
          v-model="form.name"
          type="text"
          placeholder="Nome do time"
          class="input"
          required
        />
      </div>
      <div class="form-group">
        <input
          v-model="form.sector"
          type="text"
          placeholder="Setor"
          class="input"
        />
      </div>
      <div class="form-actions">
        <button type="submit" class="btn-submit">
          {{ form.id ? 'Atualizar' : 'Adicionar' }} Time
        </button>
        <!-- Botão de cancelar -->
        <button 
          v-if="form.id" 
          type="button" 
          @click="cancelEdit" 
          class="btn-cancel">
          Cancelar
        </button>
      </div>
    </form>

    <!-- Lista de times -->
    <ul class="team-list">
      <li
        v-for="team in teams"
        :key="team.id"
        class="team-item"
      >
        <div>
          <h2 class="team-name">{{ team.name }}</h2>
          <p class="team-sector">{{ 'Setor: '+team.sector || 'Sem setor' }}</p>
        </div>
        <div class="team-actions">
          <button
            @click="editTeam(team)"
            class="btn-action"
          >
            Editar
          </button>
          <button
            @click="deleteTeam(team.id)"
            class="btn-action"
          >
            Excluir
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, reactive, onMounted } from 'vue';

export default {
  name: 'TeamManager',
  setup() {
    const apiUrl = import.meta.env.VITE_URL_API;
    const authToken = sessionStorage.getItem('auth_token');

    const teams = ref([]);
    const form = reactive({ id: null, name: '', sector: '' });

    const axiosInstance = axios.create({
      baseURL: apiUrl,
      headers: {
        Authorization: `Bearer ${authToken}`,
      },
    });

    // Fetch initial data
    const fetchTeams = async () => {
      try {
        const { data } = await axiosInstance.get('/teams');
        teams.value = data;
      } catch (error) {
        console.error('Erro ao buscar times:', error);
      }
    };

    // Add or update team
    const handleSubmit = async () => {
      try {
        if (form.id) {
          await axiosInstance.put(`/teams/${form.id}`, form);
          alert('Time atualizado com sucesso!');
        } else {
          await axiosInstance.post('/teams', form);
          alert('Time adicionado com sucesso!');
        }
        resetForm();
        fetchTeams();
      } catch (error) {
        console.error('Erro ao salvar time:', error);
      }
    };

    // Edit team
    const editTeam = (team) => {
      form.id = team.id;
      form.name = team.name;
      form.sector = team.sector;
    };

    // Cancel the edit and reset form
    const cancelEdit = () => {
      resetForm();
    };

    // Reset the form to default state
    const resetForm = () => {
      form.id = null;
      form.name = '';
      form.sector = '';
    };

    // Delete team
    const deleteTeam = async (id) => {
      if (!confirm('Deseja realmente excluir este time?')) return;
      try {
        await axiosInstance.delete(`/teams/${id}`);
        alert('Time excluído com sucesso!');
        fetchTeams();
      } catch (error) {
        console.error('Erro ao excluir time:', error);
      }
    };

    onMounted(fetchTeams);

    return {
      teams,
      form,
      fetchTeams,
      handleSubmit,
      editTeam,
      deleteTeam,
      cancelEdit,
    };
  },
};
</script>

<style scoped>

/* Importando a fonte Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

/* Estilos gerais */
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

/* Estilos do botão de adicionar/atualizar */
.btn-submit {
  padding: 0.6rem 1.2rem;
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

/* Botão de cancelar */
.btn-cancel {
  padding: 0.6rem 1.2rem;
  background-color: #f0f0f0;
  color: #333;
  font-size: 1rem;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-cancel:hover {
  background-color: #e0e0e0;
}

/* Estilos da lista de times */
.team-list {
  list-style-type: none;
  padding: 0;
  margin-top: 2rem;
}

.team-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #f9f9f9;
  border-radius: 6px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  margin-bottom: 1rem;
}

.team-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: #333;
}

.team-sector {
  color: #777;
}

.team-actions {
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
