import Vue from 'vue'
import Router from 'vue-router'

import Debug from './views/admin/Debug.vue'

import Login from './views/my/Login.vue'
import Logout from './views/my/Logout.vue'
import AdminCelebrationSets from './views/admin/AdminCelebrationSets.vue'
import AdminItem from './views/admin/AdminItem.vue'
import AdminItemsSort from './views/admin/AdminItemsSort.vue'
import AdminPost from './views/admin/AdminPost.vue'
import AdminTeams from './views/admin/AdminTeams.vue'
import AdminTrainings from './views/admin/AdminTrainings.vue'

import MyGoals from './views/my/MyGoals.vue'
import MyDisciples from './views/my/MyDisciples.vue'
import MyItem from './views/my/MyItem.vue'
import MyMonth from './views/my/MyMonth.vue'
import MyPrayers from './views/my/MyPrayers.vue'
import MyPrayerUpdate from './views/my/MyPrayerUpdate.vue'
import MyProfile from './views/my/MyProfile.vue'
import MyPasswordReset from './views/my/MyPasswordReset.vue'
import MyPasswordSent from './views/my/MyPasswordSent.vue'
import MySettingsToday from './views/my/MySettingsToday.vue'
import MyYear from './views/my/MyYear.vue'
import MyToday from './views/my/MyToday.vue'
import MyTodayUpdate from './views/my/MyTodayUpdate.vue'
import NotFoundComponent from './views/NotFound.vue'
import TeamEvent from './views/team/TeamEvent.vue'
import TeamEvents from './views/team/TeamEvents.vue'
import TeamGoals from './views/team/TeamGoals.vue'
import TeamItem from './views/team/TeamItem.vue'
import TeamMonth from './views/team/TeamMonth.vue'
import TeamMemberReports from './views/team/TeamMemberReports.vue'
import TeamMemberProfile from './views/team/TeamMemberProfile.vue'
import TeamProfile from './views/team/TeamProfile.vue'
import TeamYear from './views/team/TeamYear.vue'
import NavBar from './components/NavBar.vue'

import OurTeam from './views/team/OurTeam.vue'
import Validate from './views/Validate.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login,
      props: false
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout,
      props: false
    },
    {
      path: '/debug',
      name: 'debugger',
      component: NavBar,
      props: false
    },

    {
      path: '/admin/item/:id?/:celebration_set?/:uid?/:tid?',
      name: 'adminItem',
      component: AdminItem,
      props: true
    },
    {
      path: '/admin/items/sort/:celebration_set',
      name: 'adminItemsSort',
      component: AdminItemsSort,
      props: true
    },
    {
      path: '/admin/post',
      name: 'adminPost',
      component: AdminPost,
      props: false
    },
    {
      path: '/admin/sets/:celebration_set?',
      name: 'adminCelebrationSets',
      component: AdminCelebrationSets,
      props: true
    },
    {
      path: '/admin/teams',
      name: 'adminTeams',
      component: AdminTeams,
      props: false
    },
    {
      path: '/admin/trainings',
      name: 'adminTrainings',
      component: AdminTrainings,
      props: false
    },

    {
      path: '/my/disciples/:uid/:tid/:year?',
      name: 'myDisciples',
      component: MyDisciples,
      props: true
    },
    {
      path: '/my/goals/:uid/:tid/:year?',
      name: 'myGoals',
      component: MyGoals,
      props: true
    },
    {
      path: '/my/item/:uid/:tid/:id?',
      name: 'myItem',
      component: MyItem,
      props: true
    },
    {
      path: '/my/month/:uid/:tid/:page?/:month?/:year?',
      name: 'myMonth',
      component: MyMonth,
      props: true
    },
    {
      path: '/my/prayer/:uid/:tid/:pid',
      name: 'myPrayerUpdate',
      component: MyPrayerUpdate,
      props: true
    },
    {
      path: '/my/prayers/:uid/:tid/:month?/:year?',
      name: 'myPrayers',
      component: MyPrayers,
      props: true
    },
    {
      path: '/my/profile/:uid',
      name: 'myProfile',
      component: MyProfile,
      props: true
    },
    {
      path: '/my/reset/:token/:uid',
      name: 'myPasswordReset',
      component: MyPasswordReset,
      props: true
    },
    {
      path: '/my/sent',
      name: 'myPasswordSent',
      component: MyPasswordSent,
      props: true
    },
    {
      path: '/my/today/:uid/:tid/',
      name: 'myToday',
      component: MyToday,
      props: true
    },
    {
      path: '/my/today/update/:uid/:tid/:todayid/:page?/:month?/:year?',
      name: 'myTodayUpdate',
      component: MyTodayUpdate,
      props: true
    },
    {
      path: '/my/today/settings/:uid/:tid/',
      name: 'myTodaySettings',
      component: MySettingsToday,
      props: true
    },
    {
      path: '/my/year/:uid/:tid/:item?/:year?',
      name: 'myYear',
      component: MyYear,
      props: true
    },

    {
      path: '/team/goals/:tid/:year?',
      name: 'teamGoals',
      component: TeamGoals,
      props: true
    },
    {
      path: '/team/item/:tid/:id?',
      name: 'teamItem',
      component: TeamItem,
      props: true
    },
    {
      path: '/team/current/:tid/:uid',
      name: 'teamMemberReports',
      component: TeamMemberReports,
      props: true
    },
    {
      path: '/team/event/:tid/:uid',
      name: 'teamEvent',
      component: TeamEvent,
      props: true
    },
    {
      path: '/team/events/:tid/:uid',
      name: 'teamEvents',
      component: TeamEvents,
      props: true
    },
    {
      path: '/team/member/:tid/:uid?',
      name: 'teamMemberProfile',
      component: TeamMemberProfile,
      props: true
    },
    {
      path: '/team/members/:tid',
      name: 'ourTeam',
      component: OurTeam,
      props: true
    },
    {
      path: '/team/profile/:tid',
      name: 'teamProfile',
      component: TeamProfile,
      props: true
    },
    {
      path: '/team/month/:tid/:page?/:month?/:year?',
      name: 'teamMonth',
      component: TeamMonth,
      props: true
    },
    {
      path: '/team/year/:uid/:tid/:item?/:year?',
      name: 'teamYear',
      component: TeamYear,
      props: true
    },

    {
      path: '/debug/',
      name: 'debug',
      component: Debug,
      props: false
    },

    {
      path: '/validate',
      name: 'validate',
      component: Validate,
      props: false
    },

    {
      path: '*',
      component: NotFoundComponent
    }
  ]
})
