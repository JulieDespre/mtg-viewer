<script setup>
import { ref, onMounted } from 'vue';
import { searchCards, fetchSetCodes } from '../services/cardService';
const searchQuery = ref('');
const setCode = ref('');
const setCodes = ref([]);
const cards = ref([]);
const loadingCards = ref(false);
const timeout = ref(null);
const search = async () => {
  if (searchQuery.value.length < 3) {
    return;
  }
  loadingCards.value = true;
  clearTimeout(timeout.value);
  timeout.value = setTimeout(async () => {
    cards.value = await searchCards(searchQuery.value, setCode.value);
    loadingCards.value = false;
  }, 400);
};
onMounted(async () => {
  setCodes.value = await fetchSetCodes();
});
</script>

<template>
    <div>
        <h1>Rechercher une Carte</h1>
      <input v-model="searchQuery" @input="search" placeholder="Nom de la carte"/>
      <select v-model="setCode" @change="search">
        <option value="">Tous les sets</option>
        <option v-for="code in setCodes" :key="code.set_code" :value="code.set_code">{{ code.set_code }}</option>
      </select>
    </div>
    <div class="card-list">
        <div v-if="loadingCards">Loading...</div>
        <div v-else>
          <div class="card-result" v-for="card in cards" :key="card.id">
            <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
              {{ card.name }} <span>({{ card.uuid }})</span>
            </router-link>
        </div>
    </div>
</div>
</template>
