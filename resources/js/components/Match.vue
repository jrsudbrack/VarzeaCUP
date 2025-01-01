<template>
    <div class="container">
      <!-- Formulário para adicionar ou editar partida -->
      <form @submit.prevent="handleSubmit" class="form">
        <div class="form-group">
          <label for="championship">Campeonato</label>
          <select v-model="form.championship_id" id="championship" class="input" required>
            <option disabled value="">Selecione um campeonato</option>
            <option v-for="championship in championships" :key="championship.id" :value="championship.id">
              {{ championship.name }} ({{ championship.year }})
            </option>
          </select>
        </div>
        <div class="form-group">
          <label for="home_team">Time da Casa</label>
          <select v-model="form.home_team_id" id="home_team" class="input" required>
            <option disabled value="">Selecione o time da casa</option>
            <option v-for="team in teams" :key="team.id" :value="team.id">
              {{ team.name }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label for="away_team">Time Visitante</label>
          <select v-model="form.away_team_id" id="away_team" class="input" required>
            <option disabled value="">Selecione o time visitante</option>
            <option v-for="team in teams" :key="team.id" :value="team.id">
              {{ team.name }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <input
            v-model="form.match_date"
            type="datetime-local"
            id="match_date"
            class="input"
            required
          />
        </div>
        <div class="form-group">
          <input
            v-model="form.round"
            type="number"
            id="round"
            class="input"
            placeholder="Rodada"
            required
          />
        </div>
        <button type="submit" class="btn-submit">
          {{ form.id ? 'Atualizar' : 'Adicionar' }} Partida
        </button>
        <button v-if="form.id" @click="cancelUpdate" type="button" class="btn-cancel">
          Cancelar
        </button>
      </form>
  
      <!-- Lista de partidas -->
      <ul class="match-list">
        <li v-for="match in matches" :key="match.id" class="match-item">
          <div>
            <h3>{{ match.home_team.name }} vs {{ match.away_team.name }}</h3>
            <p>{{ match.championship.name }} - {{ formatDate(match.match_date) }} - Rodada {{ match.round }}</p>
            <p>Placar:
              <input 
                v-model.number="match.home_team_score" 
                type="number" 
                min="0" 
                class="score-input" 
                placeholder="Placar Casa" 
              />
              x
              <input 
                v-model.number="match.away_team_score" 
                type="number" 
                min="0" 
                class="score-input" 
                placeholder="Placar Visitante" 
              />
            </p>
          </div>
          <div class="match-actions">
            <button @click="saveScore(match)" class="btn-action">Salvar Placar</button>
            <button @click="editMatch(match)" class="btn-action">Editar</button>
            <button @click="deleteMatch(match.id)" class="btn-action">Excluir</button>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, reactive, onMounted } from 'vue';
  
  export default {
    name: 'MatchManager',
    setup() {
      const apiUrl = import.meta.env.VITE_URL_API;
      const authToken = sessionStorage.getItem('auth_token');
  
      const teams = ref([]);
      const championships = ref([]);
      const matches = ref([]);
      const form = reactive({
        id: null,
        championship_id: '',
        home_team_id: '',
        away_team_id: '',
        match_date: '',
        round: '',
        home_team_score: 0,
        away_team_score: 0
      });
  
      const axiosInstance = axios.create({
        baseURL: apiUrl,
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      });
  
      // Buscar times e campeonatos
      const fetchTeamsAndChampionships = async () => {
        try {
          const [teamsData, championshipsData] = await Promise.all([
            axiosInstance.get('/teams'),
            axiosInstance.get('/championships'),
          ]);
  
          teams.value = teamsData.data;
          championships.value = championshipsData.data;
        } catch (error) {
          console.error('Erro ao buscar times e campeonatos:', error);
        }
      };
  
      // Buscar partidas
      const fetchMatches = async () => {
        try {
          const { data } = await axiosInstance.get('/matches');
          matches.value = data.map((match) => ({
            ...match,
            home_team_name: match.home_team.name,
            away_team_name: match.away_team.name,
            championship_name: match.championship.name,
          }));
        } catch (error) {
          console.error('Erro ao buscar partidas:', error);
        }
      };
  
      // Adicionar ou atualizar partida
      const handleSubmit = async () => {
        try {
          if (form.id) {
            await axiosInstance.put(`/matches/${form.id}`, form);
            alert('Partida atualizada com sucesso!');
          } else {
            await axiosInstance.post('/matches', form);
            alert('Partida adicionada com sucesso!');
          }
          resetForm();
          fetchMatches();
        } catch (error) {
          console.error('Erro ao salvar partida:', error);
        }
      };
  
      // Editar partida
      const editMatch = (match) => {
        form.id = match.id;
        form.championship_id = match.championship.id; // Corrigido para o ID do campeonato
        form.home_team_id = match.home_team.id;
        form.away_team_id = match.away_team.id;
        form.match_date = match.match_date ? match.match_date.substring(0, 16) : ''; // Formatação para datetime-local
        form.round = match.round;
        form.away_team_score = match.away_team_score,
        form.home_team_score = match.home_team_score
      };
  
      // Cancelar atualização
      const cancelUpdate = () => {
        resetForm();
      };
  
      // Excluir partida
      const deleteMatch = async (id) => {
        if (!confirm('Deseja realmente excluir esta partida?')) return;
        try {
          await axiosInstance.delete(`/matches/${id}`);
          alert('Partida excluída com sucesso!');
          fetchMatches();
        } catch (error) {
          console.error('Erro ao excluir partida:', error);
        }
      };
  
      // Salvar placar
      const saveScore = async (match) => {
        console.log(match)
        try {
          const updatedMatch = {
            championship_id: match.championship.id,
            home_team_id: match.home_team.id,
            away_team_id: match.away_team.id,
            match_date: match.match_date,
            round: match.round,
            home_team_score: match.home_team_score,
            away_team_score: match.away_team_score
          };
          await axiosInstance.put(`/matches/${match.id}`, updatedMatch);
          alert('Placar salvo com sucesso!');
          fetchMatches();
        } catch (error) {
          console.error('Erro ao salvar placar:', error);
        }
      };
  
      // Resetar o formulário
      const resetForm = () => {
        form.id = null;
        form.championship_id = '';
        form.home_team_id = '';
        form.away_team_id = '';
        form.match_date = '';
        form.round = '';
      };
  
      // Formatar data
      const formatDate = (date) => {
        const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(date).toLocaleString('pt-BR', options);
      };
  
      // Carregar dados ao montar o componente
      onMounted(() => {
        fetchTeamsAndChampionships();
        fetchMatches();
      });
  
      return {
        teams,
        championships,
        matches,
        form,
        fetchTeamsAndChampionships,
        fetchMatches,
        handleSubmit,
        editMatch,
        cancelUpdate,
        deleteMatch,
        saveScore,
        formatDate,
      };
    },
  };
  </script>
  
  <style scoped>
  /* Fontes do Google */
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
  
  body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
  }
  
  .container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
  }
  
  .input {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s;
  }
  
  .input:focus {
    border-color: #4a90e2;
  }
  
  .btn-submit,
  .btn-action,
  .btn-cancel {
    padding: 0.6rem;
    background-color: #4a90e2;
    color: white;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    border: none;
  }
  
  .btn-submit:hover,
  .btn-action:hover,
  .btn-cancel:hover {
    background-color: #357abd;
  }
  
  .btn-cancel {
    background-color: #e0e0e0;
    color: #333;
  }
  
  .match-list {
    margin-top: 2rem;
    list-style-type: none;
    padding: 0;
  }
  
  .match-item {
    background-color: #f4f4f9;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 6px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }
  
  .score-input {
    width: 60px;
    padding: 0.4rem;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .match-actions {
    display: flex;
    gap: 1rem;
  }
  </style>
  