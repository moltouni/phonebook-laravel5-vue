<template>
  <div>
    <GlobalEvents @keyup.191="focusSearch" />

    <nav class="navigation fixed-top">
      <!-- Filter Contacts -->

      <div class="dropdown" v-if="!searchActive">
        <!-- Dropdown button -->
        <a
          :class="{'btn dropdown-toggle': true, 'btn-secondary': this.filter, 'btn-outline-secondary': !this.filter}"
          data-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="true"
          aria-expanded="false"
        >{{filterText}}</a>

        <!-- Dropdown Items -->
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <router-link
            v-if="filter !== 'all'"
            :to="{ name: 'contact-listing-all' }"
            class="dropdown-item"
          >All</router-link>

          <router-link
            v-if="filter !== 'favorite'"
            :to="{ name: 'contact-listing-favorites' }"
            class="dropdown-item"
          >Favorites</router-link>
        </div>
      </div>

      <!-- Add Contact -->

      <router-link class="contact-add" :to="{name: 'contact-add'}" v-if="!searchActive">
        <button
          :class="{'btn': true, 'btn-secondary': routeName == 'contact-add', 'btn-outline-secondary': routeName !== 'contact-add'}"
          type="button"
        >
          <icon name="plus"></icon>
        </button>
      </router-link>

      <!-- Search -->

      <div :class="{'searchContainer': true, 'searchActive': searchActive}">
        <button
          :class="{'btn btn-outline-secondary': true, 'searchActive': searchActive}"
          type="button"
          @click="searchActive = !searchActive"
        >
          <icon v-if="!searchActive" name="search"></icon>
          <icon v-else name="times"></icon>
        </button>

        <input
          v-if="searchActive"
          @input="search"
          v-model="query"
          placeholder="Search"
          type="text"
          ref="search"
        />
      </div>

      <!-- Logout -->

      <button @click="logout" class="btn btn-outline-secondary logout" v-if="!searchActive">Logout</button>
    </nav>

    <router-view></router-view>
  </div>
</template>

<script>
import _ from "lodash";
import GlobalEvents from "vue-global-events";

export default {
  components: {
    GlobalEvents
  },
  data() {
    return {
      query: null,
      searchActive: false,
      filter: null,
      routeName: null
    };
  },
  mounted() {
    this.routeName = this.$router.currentRoute.name;

    if (this.routeName == "contact-listing-favorites") {
      this.filter = "favorite";
    } else if (this.routeName == "contact-listing-all") {
      this.filter = "all";
    } else if (this.routeName == "contact-listing-search") {
      this.searchActive = true;
      this.query = this.$route.query.q;

      if (this.query == "") {
        this.$router.push({ name: "contact-listing-all" });
      }
    } else {
      this.filter = null;
    }
  },

  methods: {
    focusSearch: function() {
      this.$nextTick(function() {
        this.$refs.search.focus();
      });
    },
    search: _.debounce(function(e) {
      this.$router.push({
        name: "contact-listing-search",
        query: { q: this.query }
      });
    }, 200),
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
    filterText() {
      if (this.filter) {
        return this.capitalize(this.filter) + " Contacts";
      }
      return "Contacts";
    }
  },
  watch: {
    searchActive(newValue, oldValue) {
      if (newValue === true) {
        this.focusSearch();
      }
    }
  }
};
</script>

<style lang="scss" scoped>
$searchFontSize: 21px;

.navigation {
  padding: 12px;
  background-color: #f5f8fa;

  .dropdown {
    display: inline-block;
    .dropdown-item.active,
    .dropdown-item:active {
      color: inherit;
      background-color: inherit;
    }
  }

  .searchContainer {
    position: relative;
    display: inline-block;
    &.searchActive {
      width: 100%;
    }

    button {
      &.searchActive {
        position: absolute;
        border-radius: 0;
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
      }
    }

    input {
      max-width: 100%;
      width: 100%;
      font-size: $searchFontSize;
      padding: 0px 48px;

      &:focus {
        outline: none;
      }
    }
  }

  .logout {
    float: right;
  }
}
</style>
