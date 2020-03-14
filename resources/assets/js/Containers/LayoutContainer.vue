<template>
  <div>
    <nav class="navigation fixed-top">
      <!-- Filter Contacts -->

      <div class="dropdown">
        <button
          :class="{'btn dropdown-toggle': true, 'btn-secondary': this.filter, 'btn-outline-secondary': !this.filter}"
          type="button"
          id="dropdownMenuButton"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >{{dropdownText}}</button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <router-link
            :to="{ name: 'contact-listing-all' }"
            v-bind:class="{ active: filter == 'all', 'dropdown-item' : true}"
          >All</router-link>

          <router-link
            :to="{ name: 'contact-listing-favorites' }"
            v-bind:class="{ active: filter == 'favorite', 'dropdown-item' : true}"
          >Favorites</router-link>
        </div>
      </div>

      <!-- Add Contact -->
      <router-link class="contact-add" :to="{name: 'contact-add'}">
        <button
          :class="{'btn': true, 'btn-secondary': routeName == 'contact-add', 'btn-outline-secondary': routeName !== 'contact-add'}"
          type="button"
        >
          <icon name="plus"></icon>
        </button>
      </router-link>

      <!-- Logout -->
      <button @click="logout" class="btn btn-outline-secondary logout">Logout</button>
    </nav>

    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {
      filter: null,
      routeName: null
    };
  },
  created() {
    this.routeName = this.$router.currentRoute.name;

    if (this.routeName == "contact-listing-favorites") {
      this.filter = "favorite";
    } else if (this.routeName == "contact-listing-all") {
      this.filter = "all";
    } else {
      this.filter = null;
    }
  },

  methods: {
    capitalize: function(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
    logout: function() {
      let token = document.head.querySelector('meta[name="csrf-token"]');

      axios.post("/logout", { _token: token.content }).then(() => {
        window.location = "/";
      });
    }
  },
  computed: {
    dropdownText() {
      if (this.filter) {
        return this.capitalize(this.filter) + " Contacts";
      }
      return "Contacts";
    }
  }
};
</script>

<style lang="scss" scoped>
.navigation {
  padding: 12px;
  .dropdown {
    display: inline-block;
  }

  .logout {
    float: right;
  }
}
</style>
