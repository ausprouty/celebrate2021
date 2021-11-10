import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import defaultUser from './default-user'
import { saveStatePlugin } from '@/utils.js' // <-- Import saveStatePlugin

Vue.use(Vuex)

const user = JSON.parse(localStorage.getItem('user')) || defaultUser

export default new Vuex.Store({
  plugins: [saveStatePlugin], // <-- Use
  state: {
    user: {},
    my: {},
    team: {
      tid: null,
      name: null,
      strategy: null,
      focus: null,
      state: null,
      game: null
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
    ],
    content: {}
  },
  mutations: {
    LOGIN_USER(state, value) {
      state.user = value[0]
      localStorage.setItem('user', JSON.stringify(state.user))
    },
    SEEING_MEMBER(state, value) {
      state.my = value[0]
      localStorage.setItem('my', JSON.stringify(state.my))
    }
  },
  actions: {
    loginUser({ commit }, [mark]) {
      commit('LOGIN_USER', [mark])
    },
    seeingMember({ commit }, [mark]) {
      commit('SEEING_MEMBER', [mark])
    }
  }
})
