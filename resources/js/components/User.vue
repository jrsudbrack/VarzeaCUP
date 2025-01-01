<template>
  <div class="user-manager">

    <!-- Mensagem de erro -->
    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>

    <!-- Formulário para adicionar ou editar usuários -->
    <form @submit.prevent="handleSubmit" class="form-container">
      <h2>{{ isEditing ? "Editar Usuário" : "Adicionar Novo Usuário" }}</h2>
      <div class="form-group">
        <label for="name">Nome:</label>
        <input
          type="text"
          id="name"
          v-model="formData.name"
          placeholder="Digite o nome"
          required
        />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input
          type="email"
          id="email"
          v-model="formData.email"
          placeholder="Digite o email"
          required
        />
      </div>
      <div class="form-group">
        <label for="password">Senha:</label>
        <input
          type="password"
          id="password"
          v-model="formData.password"
          placeholder="Digite a senha"
          :required="!isEditing"
        />
      </div>
      <div class="form-buttons">
        <button type="submit" class="btn-submit">
          {{ isEditing ? "Salvar Alterações" : "Adicionar" }}
        </button>
        <button
          type="button"
          v-if="isEditing"
          class="btn-cancel"
          @click="cancelEdit"
        >
          Cancelar
        </button>
      </div>
    </form>

    <!-- Tabela de usuários -->
    <h2>Lista de Usuários</h2>
    <table class="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <button class="btn-edit" @click="editUser(user)">Editar</button>
            <button class="btn-delete" @click="deleteUser(user.id)">
              Excluir
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      users: [],
      formData: {
        id: null,
        name: "",
        email: "",
        password: "",
      },
      isEditing: false,
      errorMessage: null,
      apiUrl: import.meta.env.VITE_URL_API + "users",
      authToken: sessionStorage.getItem("auth_token"),
    };
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get(this.apiUrl, {
          headers: {
            Authorization: `Bearer ${this.authToken}`,
          },
        });
        this.users = response.data;
        this.errorMessage = null; // Limpa mensagens de erro anteriores
      } catch (error) {
        this.errorMessage = "Erro ao buscar usuários. Tente novamente mais tarde.";
      }
    },
    async handleSubmit() {
      try {
        if (this.isEditing) {
          await axios.put(
            `${this.apiUrl}/${this.formData.id}`,
            this.formData,
            {
              headers: {
                Authorization: `Bearer ${this.authToken}`,
              },
            }
          );
          alert("Usuário atualizado com sucesso!");
        } else {
          await axios.post(this.apiUrl, this.formData, {
            headers: {
              Authorization: `Bearer ${this.authToken}`,
            },
          });
          alert("Usuário adicionado com sucesso!");
        }
        this.resetForm();
        this.fetchUsers();
      } catch (error) {
        this.errorMessage = error.response?.data?.message || "Erro ao salvar usuário.";
      }
    },
    editUser(user) {
      this.isEditing = true;
      this.formData = { ...user, password: "" };
    },
    cancelEdit() {
      this.resetForm();
    },
    async deleteUser(id) {
      if (!confirm("Deseja realmente excluir este usuário?")) return;
      try {
        await axios.delete(`${this.apiUrl}/${id}`, {
          headers: {
            Authorization: `Bearer ${this.authToken}`,
          },
        });
        alert("Usuário excluído com sucesso!");
        this.fetchUsers();
      } catch (error) {
        this.errorMessage = error.response?.data?.message || "Erro ao excluir usuário.";
      }
    },
    resetForm() {
      this.isEditing = false;
      this.formData = {
        id: null,
        name: "",
        email: "",
        password: "",
      };
      this.errorMessage = null;
    },
  },
};
</script>

<style scoped>
.user-manager {
  max-width: 800px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
  padding-bottom: 100px;
}

h1,
h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.error-message {
  background-color: #ffdddd;
  color: #d8000c;
  padding: 10px;
  border: 1px solid #d8000c;
  border-radius: 4px;
  margin-bottom: 15px;
}

.form-container {
  background: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
}

input:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

.form-buttons {
  display: flex;
  justify-content: space-between;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.btn-submit {
  background-color: #007bff;
  color: white;
}

.btn-submit:hover {
  background-color: #0056b3;
}

.btn-cancel {
  background-color: #f44336;
  color: white;
}

.btn-cancel:hover {
  background-color: #c62828;
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.user-table th,
.user-table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

.user-table th {
  background-color: #f4f4f4;
  font-weight: bold;
}

.btn-edit {
  background-color: #ffc107;
  color: white;
}

.btn-edit:hover {
  background-color: #ff9800;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.btn-delete:hover {
  background-color: #c62828;
}
</style>
