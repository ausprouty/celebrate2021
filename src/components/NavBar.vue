<template>
  <div id="nav">
    <router-link to="/">
      <img
        class="nav-icon"
        alt="Home"
        src="/images/menu/ribbons/MyTopRibbon600.png"
      />
    </router-link>

    <div class="container">
      <div class="back hand float" @click="goBack()">
        <img class="icon hand" src="/images/icons/admin/back_48x48.png" />
      </div>
      <div class="hand pad float" @click="showMyMenu()">
        <img class="icon hand" src="/images/icons/personal/person_48x48.png" />
      </div>

      <div class="hand team float pad" @click="showTeamMenu()">
        <img class="icon hand" src="/images/icons/team/group_48x48.png" />
      </div>
      <div class="hand team float pad" @click="showAdminMenu()">
        <img class="icon hand" src="/images/icons/admin/admin_48x48.png" />
      </div>
      <div class="my float pad">
        <img class="member" v-bind:src="appDir.members + image" />
      </div>
      <div class="breadcrumb float-right">{{ this.breadcrumb }}</div>
    </div>
    <div id="admin_menu" class="dropdown-content-admin">
      <div
        v-for="menuItem in admin_menu"
        :key="menuItem.index"
        :menuItem="menuItem"
      >
        <div
          class="item"
          style="cursor:pointer"
          @click="setNewSelectedOption(menuItem)"
        >
          {{ menuItem.value }}
        </div>
      </div>
    </div>
    <div id="my_menu" class="dropdown-content-my">
      <div
        v-for="menuItem in my_menu"
        :key="menuItem.index"
        :menuItem="menuItem"
      >
        <div
          class="item"
          style="cursor:pointer"
          @click="setNewSelectedOption(menuItem)"
        >
          {{ menuItem.value }}
        </div>
      </div>
    </div>
    <div id="team_menu" class="dropdown-content-team">
      <div
        v-for="menuItem in team_menu"
        :key="menuItem.index"
        :menuItem="menuItem"
      >
        <div
          class="item"
          style="cursor:pointer"
          @click="setNewSelectedOption(menuItem)"
        >
          {{ menuItem.value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  computed: mapState(['user', 'my', 'appDir']),
  props: {
    image: String,
    time: String,
    breadcrumb: String
  },
  data() {
    return {
      authorized: true,
      show_team: false,
      show_my: false,
      show_admin: false,
      admin_menu: [
        {
          index: 0,
          value: 'Teams',
          show: true,
          link: 'adminTeams',
          url: 'admin/teams/'
        },
        {
          index: 1,
          value: 'Trainings',
          show: true,
          link: 'adminTrainings',
          url: 'admin/trainings/'
        },
        {
          index: 2,
          value: 'Post to Cru',
          show: true,
          link: 'adminPost',
          url: 'admin/post/'
        },
        {
          index: 3,
          value: 'Celebration Sets',
          show: true,
          link: 'adminCelebrationSets',
          url: 'admin/sets/'
        }
      ],
      my_menu: [
        {
          index: 0,
          value: 'Today',
          show: true,
          link: 'myToday',
          url: 'my/today/'
        },
        {
          index: 1,
          value: "Let's Pray",
          show: true,
          link: 'myPrayers',
          url: 'my/prayers/'
        },
        {
          index: 2,
          value: 'My Disciples',
          show: true,
          link: 'myDisciples',
          url: 'my/disciples/'
        },
        {
          index: 3,
          value: 'My Month',
          show: true,
          link: 'myMonth',
          url: 'my/month/'
        },
        {
          index: 4,
          value: 'My Goals',
          show: true,
          link: 'myGoals',
          url: 'my/goals/'
        },
        {
          index: 5,
          value: 'My Year',
          show: true,
          link: 'myYear',
          url: 'my/year/'
        },

        {
          index: 6,
          value: 'My Profile',
          show: true,
          link: 'myProfile',
          url: 'my/profile/'
        },

        {
          index: 8,
          value: 'Logout',
          show: true,
          link: 'logout',
          url: '/logout/'
        }
      ],
      team_menu: [
        {
          index: 0,
          value: 'Our Team',
          show: true,
          link: 'ourTeam',
          url: '/team/members/'
        },
        {
          index: 1,
          value: 'Monthly Progress',
          show: true,
          link: 'teamMonth',
          url: '/team/month/'
        },
        {
          index: 2,
          value: 'Yearly Progress',
          show: true,
          link: 'teamYear',
          url: '/team/year/'
        },
        {
          index: 3,
          value: 'Team Goals',
          show: false,
          link: 'teamGoals',
          url: '/team/goals/'
        },
        {
          index: 4,
          value: 'Team Events',
          show: false,
          link: 'teamEvents',
          url: '/team/events/'
        },
        {
          index: 5,
          value: 'Team Profile',
          show: true,
          link: 'teamProfile',
          url: '/team/profile/'
        }
      ]
    }
  },
  methods: {
    goBack() {
      window.history.back()
    },
    showAdminMenu() {
      if (!this.show_admin) {
        document.getElementById('admin_menu').style.display = 'block'
        this.show_my = true
        document.getElementById('my_menu').style.display = 'none'
        this.show_my = true
        document.getElementById('team_menu').style.display = 'none'
        this.show_team = false
      } else {
        document.getElementById('admin_menu').style.display = 'none'
        this.show_my = false
      }
    },
    showMyMenu() {
      if (!this.show_my) {
        document.getElementById('my_menu').style.display = 'block'
        this.show_my = true
        document.getElementById('admin_menu').style.display = 'none'
        this.show_admin = false
        document.getElementById('team_menu').style.display = 'none'
        this.show_team = false
      } else {
        document.getElementById('my_menu').style.display = 'none'
        this.show_my = false
      }
    },
    showTeamMenu() {
      if (!this.show_team) {
        document.getElementById('team_menu').style.display = 'block'
        this.show_team = true
        document.getElementById('admin_menu').style.display = 'none'
        this.show_admin = false
        document.getElementById('my_menu').style.display = 'none'
        this.show_my = false
      } else {
        document.getElementById('my_team').style.display = 'none'
        this.show_team = false
      }
    },
    setNewSelectedOption(selectedOption) {
      console.log(this.$route)
      if (selectedOption.link == this.$route.name) {
        document.getElementById('admin_menu').style.display = 'none'
        this.show_admin = false
        document.getElementById('my_menu').style.display = 'none'
        this.show_my = false
        document.getElementById('team_menu').style.display = 'none'
        this.show_team = false
        return
      }

      if (typeof this.$route.params.uid == 'undefined') {
        this.$route.params.uid = this.my.uid
      }
      if (typeof this.$route.params.tid == 'undefined') {
        this.$route.params.tid = this.my.team
      }
      this.$router.push({
        name: selectedOption.link,
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid
        }
      })
    }
  },
  created() {
    this.authorized = true
  }
}
</script>
<style scoped>
div.float {
  display: inline;
}
.pad {
  padding-left: 10px;
}
.my {
  padding-left: 40px;
}
div.breadcrumb {
  color: grey;
  font-size: 12px;
  float: right;
}

<style scoped>
/*//https://www.w3schools.com/howto/howto_css_dropdown.asp*/

/* Dropdown Button */
.dropbtn {
  background-color: #4caf50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */

.dropdown-content-admin {
  display: none;
  position: absolute;
  background-color: red;

  min-width: 300px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}
.dropdown-content-my {
  display: none;
  position: absolute;
  background-color: blue;

  min-width: 300px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}
.dropdown-content-team {
  display: none;
  position: absolute;
  background-color: orange;

  min-width: 300px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}
.item {
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 10px;
  color: white;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content .item:hover {
  background-color: red;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>
