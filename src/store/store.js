import Vue from 'vue'
import Vuex from 'vuex'
import defaultUser from './default-user'
import { saveStatePlugin } from '@/utils.js' // <-- Import saveStatePlugin

Vue.use(Vuex)

const user = JSON.parse(localStorage.getItem('user')) || defaultUser

export default new Vuex.Store({
  plugins: [saveStatePlugin], // <-- Use
  state: {
    user: {
      uid: null
    },
    my: {}, // may be used by teamleder to look at other people's records
    member: {
      uid: null
    },
    team: {
      tid: null,
      name: null,
      strategy: null,
      focus: null,
      state: null,
      game: null
    },
    teams: {},
    itemsToday: {},
    appDir: {
      css: '/content/',
      styles: '/styles/',
      icons: '/images/icons/',
      images: '/images/',
      members: '/images/members/',
      page_images: '/images/pages/'
    },
    revision: '0.1',
    baseURL: './',
    cssURL: './content/',
    standard: {
      image_dir: '',
      image: '',
      rldir: 'ltr',
      css: ''
    },
    states: ['all', 'ACT', 'NSW', 'NT', 'QLD', 'SA', 'VIC', 'WA'],
    strategies: ['GCM'],
    focus_areas: ['Cru', 'GCM', 'Generations', 'MyFriends', 'Shiftm2M'],
    months: [
      '',
      'January',
      'February',
      'March',
      'April',
      'May',
      'June',
      'July',
      'August',
      'September',
      'October',
      'November',
      'December'
    ],
  },
  mutations: {
    LOGIN_USER(state, value) {
      state.user = value[0]
    },
    SEEING_MEMBER(state, value) {
      state.my = value
    },
    SET_ITEMS_TODAY(state, value) {
      state.itemsToday = value
    },
    SET_MEMBER(state, value) {
      state.member = value
    },
    SET_TEAM(state, value) {
      state.team = value
    },
    SET_TEAMS(state, value) {
      state.teams = value
    },
    SET_USER(state, value) {
      state.team = value
    }
  },
  actions: {
    loginUser({ commit }, [mark]) {
      commit('LOGIN_USER', [mark])
    },
    seeingMember({ commit }, mark) {
      commit('SEEING_MEMBER', mark)
    },
    setItemsToday({ commit }, mark) {
      commit('SET_ITEMS_TODAY', mark)
    },
    setMember({ commit }, mark) {
      commit('SET_MEMBER', mark)
    },
    setTeam({ commit }, mark) {
      commit('SET_TEAM', mark)
    },
    setTeams({ commit }, mark) {
      commit('SET_TEAMS', mark)
    },
    setUser({ commit }, mark) {
      commit('SET_USER', mark)
    }
  }
})
