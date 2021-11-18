import Vue from 'vue'
import Vuex from 'vuex'

import { saveStatePlugin } from '@/utils.js' // <-- Import saveStatePlugin

Vue.use(Vuex)
Vue.config.devtools = true

export default new Vuex.Store({
  plugins: [saveStatePlugin], // <-- Use
  state: {
    user: {
      uid: null
    },
    viewing: {
      user: {},
      member: {},
      team: {},
      teams: {},
      itemsToday:{}
    },
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
    ]
  },
  mutations: {
    LOGIN_USER(state, value) {
      state.user = value[0]
      localStorage.setItem('user', JSON.stringify(state.user))
      state.viewing.user = value[0]
      localStorage.setItem('viewing_user', JSON.stringify(state.user))
    },
    SEEING_MEMBER(state, value) {
      state.viewing.member = value
      localStorage.setItem('viewing_member', JSON.stringify(value))
    },
    SET_ITEMS_TODAY(state, value) {
      state.viewing.itemsToday = value
      localStorage.setItem('viewing_itemsToday', JSON.stringify(value))
    },
    SET_MEMBER(state, value) {
      state.viewing.member = value
      localStorage.setItem('viewing_member', JSON.stringify(value))
    },
    SET_TEAM(state, value) {
      state.viewing.team = value
      localStorage.setItem('viewing_team', JSON.stringify(value))
    },
    SET_TEAMS(state, value) {
      state.viewing.teams = value
    },
    SET_USER(state, value) {
      state.viewing.user = value
      localStorage.setItem('viewing_user', JSON.stringify(value))
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
