<template>
  <div class="contact-listing">
    <GlobalEvents @keyup.191="focusSearch" />

    <div class="search">
      <input type="text" ref="search" @input="search" v-model="query" placeholder="Search" />
    </div>

    <pulse-loader class="loader" :loading="loading" size="16px"></pulse-loader>

    <div v-if="!loading" class="contacts">
      <contact-component
        @favoriteToggle="favoriteToggle"
        @destroy="destroy"
        v-for="contact in contacts"
        :key="contact.id"
        :contact="contact"
      ></contact-component>

      <div v-if="filter == 'search' && !contacts.length">
        <h3>You've lost me...</h3>
      </div>
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import GlobalEvents from "vue-global-events";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";
import ContactComponent from "../Components/ContactComponent";

export default {
  components: {
    PulseLoader,
    GlobalEvents,
    ContactComponent
  },
  created() {
    let routeName = this.$router.currentRoute.name;

    if (routeName == "contact-listing-favorites") {
      this.filter = "favorites";
      this.apiUrl = "/api/contacts?search=favorite:1";
    } else if (routeName == "contact-listing-all") {
      this.filter = "all";
      this.apiUrl = "/api/contacts";
    } else if (routeName == "contact-listing-search") {
      this.query = this.$route.query.q;

      if (this.query == "") {
        this.$router.push({ name: "contact-listing-all" });
      }

      this.filter = "search";
      this.apiUrl = `/api/contacts?searchFields=first_name:like;last_name:like;email:like&search=${this.query}`;
    }
  },
  mounted() {
    this.focusSearch();
    this.getContacts();
  },
  data() {
    return {
      query: null,
      contacts: [],
      loading: true,

      apiUrl: null,
      filter: null
    };
  },
  methods: {
    focusSearch: function() {
      this.$refs.search.focus();
    },

    search: _.debounce(function(e) {
      this.$router.push({
        name: "contact-listing-search",
        query: { q: this.query }
      });
    }, 200),

    getContacts: function() {
      this.loading = true;

      axios.get(this.apiUrl).then(response => {
        this.contacts = response.data.data;
        this.loading = false;
      });
    },

    favoriteToggle: function(contact) {
      let favorite_new = contact.favorite ? 0 : 1;
      const url = `/api/contacts/${contact.id}`;
      let data = new FormData();
      data.append("_method", "PATCH");
      data.append("favorite", favorite_new);

      axios.post(url, data).then(response => {
        this.getContacts();
      });
    },
    destroy: function(contact) {
      const url = `/api/contacts/${contact.id}`;
      axios.delete(url).then(response => {
        this.getContacts();
      });
    }
  }
};
</script>

<style lang="scss" scoped>
$margin: 64px;
$borderRadius: 8px;

$contactWidth: 420px;
$contactHeight: $contactWidth/1.7;

$searchFontSize: 24px;

.contact-listing {
  .contacts,
  .search {
    margin-top: $margin;
  }

  .search {
    text-align: center;
    input {
      max-width: 100%;
      width: 600px;
      font-size: $searchFontSize;
      padding: 8px 16px;

      &:focus {
        outline: none;
      }
    }
  }

  .loader {
    text-align: center;
    margin: $margin;
  }

  .contacts {
    text-align: center;
    padding: $margin / 2;
    .contact-add {
      display: inline-block;
      overflow: hidden;
      text-align: center;
      background: #eee;
      border: 3px dashed #ddd;
      border-radius: $borderRadius;
      padding: 80px;
      width: $contactWidth;
      max-width: 100%;
      height: $contactHeight;
      font-size: 64px;
      margin: 8px;
    }
  }
}
</style>
