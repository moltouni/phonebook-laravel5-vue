window.Vue = require('vue')
import VueRouter from 'vue-router'
window.Vue.use(VueRouter)

const LayoutContainer = require('./Containers/LayoutContainer')
const ContactListingContainer = require('./Containers/ContactListingContainer')
const ContactSingleContainer = require('./Containers/ContactSingleContainer')
const ContactFormContainer = require('./Containers/ContactFormContainer')

const routes = [
    {
        path: '',
        component: LayoutContainer,
        children: [
            {
                path: '',
                component: ContactListingContainer,
                name: 'contact-listing-all'
            },
            {
                path: 'search',
                component: ContactListingContainer,
                name: 'contact-listing-search'
            },            
            {
                path: 'favorites',
                component: ContactListingContainer,
                name: 'contact-listing-favorites'
            },
            {
                path: '/contacts/add',
                component: ContactFormContainer,
                name: 'contact-add'
            },            
            {
                path: '/contacts/:contact/edit',
                component: ContactFormContainer,
                name: 'contact-single-edit'
            },
            {
                path: '/contacts/:contact',
                component: ContactSingleContainer,
                name: 'contact-single'
            },
        ]
    }        
]

export default new VueRouter({
    routes,
	mode: 'history',
    saveScrollPosition: true,    
})