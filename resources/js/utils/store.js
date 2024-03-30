import { ref } from 'vue';

const state = {
  isLoggedIn: ref(false),
  currentUser: ref(null),
};

// Optional getters (computed properties)
const getters = {
  isAuthenticated() {
    return state.isLoggedIn.value;
  },
  getCurrentUser() {
    return state.currentUser.value
  }
};

// Optional mutations (for direct state updates)
const mutations = {
  login(user) {
    state.isLoggedIn.value = true;
    state.currentUser.value = user;
  },
};

// Optional: Access function for store (Vue 3)
export function useStore() {
  return {
    state, // Expose state properties
    getters, // Expose getters (if used)
    mutations, // Expose mutations (if used)
  };
}