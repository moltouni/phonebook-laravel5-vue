<template>
  <div class="container">
    <div class="contact-container" v-if="contact">
      <div class="row">
        <div class="col-md-4 contact-left">
          <!-- Avatar -->

          <avatar
            :username="contact.first_name + contact.last_name"
            :src="contact.avatar"
            :size="128"
            class="contact-avatar"
          ></avatar>

          <!-- Controls -->

          <div class="contact-controls">
            <!-- Favorite toggle -->

            <button
              @click="toggleContactFavorite(contact)"
              class="favorite contact-edit-button btn btn-sm btn-outline-success"
            >
              <icon v-if="!contact.favorite" class="icon" name="regular/heart"></icon>
              <icon v-else class="icon" name="heart"></icon>
            </button>

            <!-- Edit button -->

            <router-link :to="{ name: 'contact-single-edit' }">
              <button class="contact-edit-button btn btn-sm btn-outline-secondary">Edit</button>
            </router-link>

            <!-- Delete button -->

            <button
              @click="deleteContact(contact.id)"
              class="contact-edit-button btn btn-sm btn-outline-danger"
            >Delete</button>
          </div>
        </div>
        <div class="col-md-8 contact-right">
          <div class="contact-info">
            <!-- Name -->

            <div class="contact-info-name">
              <h3>{{ contact.first_name }} {{ contact.last_name }}</h3>
            </div>

            <!-- Email -->

            <div class="contact-info-email">
              <a :href="'mailto:' + contact.email">{{ contact.email }}</a>
            </div>
          </div>

          <!-- Numbers -->

          <div class="contact-phones" v-if="phones">
            <div class="contact-phones-item" v-for="phone in phones" :key="phone.id">
              <a :href="'tel:' + phone.number" class="contact-phones-item-number">
                <h5 class="contact-phones-item-label">{{phone.label}}</h5>
                <h5>
                  <icon name="phone"></icon>
                  {{phone.number}}
                </h5>
              </a>
            </div>

            <div v-if="!phones.length">
              <span>No numbers to show.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from "vue-avatar";

export default {
  components: {
    Avatar
  },

  mounted() {
    let contactId = this.$route.params.contact;
    this.getContact(contactId);
    this.getContactPhones(contactId);
  },
  data() {
    return {
      contact: null,
      phones: null
    };
  },
  methods: {
    /**
     * Get Contact
     *
     * @param {Number} contactId Contact ID
     */
    getContact: function(contactId) {
      let url = `/api/contacts/${contactId}`;
      axios.get(url).then(response => {
        this.contact = response.data.data;
      });
    },

    /**
     * Get Contact Phones
     *
     * @param {Number} contactId Contact ID
     */
    getContactPhones: function(contactId) {
      let url = `/api/contacts/${contactId}/phones`;
      axios.get(url).then(response => {
        this.phones = response.data.data;
      });
    },

    /**
     * Delete Contact
     *
     * @param {Number} contactId Contact ID
     */
    deleteContact: function(contactId) {
      const url = `/api/contacts/${contactId}`;
      axios.delete(url).then(response => {
        this.$router.push({ name: "contact-listing-all" });
      });
    },

    /**
     * Toggle Favorite Flag for the Contact
     *
     * @param {Object} contact Contact
     */
    toggleContactFavorite: function(contact) {
      let favoriteNew = contact.favorite ? 0 : 1;
      const url = `/api/contacts/${contact.id}`;

      let data = new FormData();
      data.append("_method", "PATCH");
      data.append("favorite", favoriteNew);

      axios.post(url, data).then(response => {
        this.getContact(contact.id);
      });
    }
  }
};
</script>

<style lang="scss" scoped>
$margin: 64px;
$background: #eee;
$border: 3px solid #ddd;

$sizeMd: 768px;
$favorite-color: #22863a;

.contact-container {
  margin: $margin / 2 0;
  padding: $margin 0;
  background: $background;
  border: $border;
  border-radius: 8px;

  .contact-left {
    text-align: center;

    .contact-avatar {
      display: inline-block;
    }

    .contact-controls {
      margin-top: $margin / 2;
      margin-bottom: $margin / 2;
    }
  }

  .contact-info {
    .contact-info-name {
      font-weight: bold;
      text-align: center;
      @media (min-width: $sizeMd) {
        text-align: left;
      }
    }

    .contact-info-email {
      text-align: center;
      @media (min-width: $sizeMd) {
        text-align: left;
      }
    }
  }

  .contact-phones {
    text-align: center;
    @media (min-width: $sizeMd) {
      text-align: left;
    }
    .contact-phones-item {
      margin-top: $margin;

      .contact-phones-item-label {
        font-weight: bold;
      }
    }
  }
}
</style>
