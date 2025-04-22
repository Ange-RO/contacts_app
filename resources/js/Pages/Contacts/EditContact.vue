<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-6 mx-auto mt-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Edit contact
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="UpdateContact">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name*</label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            v-model="form.name"
                                            id="name"
                                            class="form-control"
                                            placeholder="Name*"    
                                        >
                                        <div
                                            v-if="form.errors.name"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email*</label>
                                        <input 
                                            type="email" 
                                            name="email" 
                                            v-model="form.email"
                                            id="email"
                                            class="form-control"
                                            placeholder="Email*"    
                                        >
                                        <div
                                            v-if="form.errors.email"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone*</label>
                                        <input 
                                            type="text" 
                                            name="phone" 
                                            v-model="form.phone"
                                            id="phone"
                                            class="form-control"
                                            placeholder="Phone*"    
                                        >
                                        <div
                                            v-if="form.errors.phone"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.phone }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="job_title" class="form-label">Job*</label>
                                        <input 
                                            type="text" 
                                            name="job_title" 
                                            id="job_title"
                                            v-model="form.job_title"
                                            class="form-control"
                                            placeholder="Job*"    
                                        >
                                        <div
                                            v-if="form.errors.job_title"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.job_title }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company*</label>
                                        <input 
                                            type="text" 
                                            name="company" 
                                            id="company"
                                            v-model="form.company"
                                            class="form-control"
                                            placeholder="Company*"    
                                        >
                                        <div
                                            v-if="form.errors.company"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.company }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country*</label>
                                        <select 
                                            v-model="form.country"
                                            @change="setCountryAlphaCode"
                                            id="country"
                                            class="form-select"
                                        >
                                            <option disabled value="">Select a country</option>
                                            <option 
                                                v-for="c in countriesList"
                                                :key="c.code"
                                                :value="c.name"
                                            >
                                                {{ c.name }}
                                            </option>
                                        </select>
                                        <div
                                            v-if="form.errors.country"
                                            class="text-white bg-danger p-2 rounded my-2"
                                        >
                                            {{ form.errors.country }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-2">
                                <button type="submit" class="btn btn-sm btn-dark me-1">
                                    Submit
                                </button>
                                <Link 
                                    href="/"
                                    class="btn btn-sm btn-warning"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>     
    </div>
</template>

<script setup>
    import { Link } from "@inertiajs/vue3"
    import { useForm } from '@inertiajs/vue3'
    import isoCountries from "i18n-iso-countries" // Renommé pour éviter la collision
    import enLocale from "i18n-iso-countries/langs/en.json"
    import { onMounted, ref } from "vue"
    isoCountries.registerLocale(enLocale)

    const countriesList = ref([]) // Variable locale pour la liste des pays

    // Appel de l’API RestCountries + tri des pays par nom
    const fetchCountries = async () => {
        try {
            const response = await axios.get('https://restcountries.com/v3.1/all')
            const allCountries = response.data
            const sorted = allCountries
                .map(c => ({
                    name: c.name.common,
                    code: c.cca2,
                    flag: c.flag || ''
                }))
                .sort((a, b) => a.name.localeCompare(b.name))

            countriesList.value = sorted
        } catch (error) {
            console.error("Erreur lors de la récupération des pays :", error)
        }
    }

    // Fonction pour définir le code alpha du pays
   const setCountryAlphaCode = (e) => {
    const selected = countriesList.value.find(c => c.name === e.target.value)
    form.country_code = selected?.code || ''
    form.country_fly = selected?.flag || '' 
}


    // Définition du formulaire
    const form = useForm({
        id: '',
        name: '',
        email: '',
        phone: '',
        job_title: '',
        company: '',
        country: '',
        country_code: '',
        country_fly: ''
    })

    // Définition des props
    const props = defineProps({
        contact: {
            type: Object,
            required: true
        }
    })

    // Fonction pour mettre à jour le contact
    const UpdateContact = () => {
        form.put(route('contacts.update', form.id))
    }

    // Remplir le formulaire avec les données du contact une fois que le composant est monté
    onMounted(() => {
        form.id = props.contact.id
        form.name = props.contact.name
        form.email = props.contact.email
        form.phone = props.contact.phone
        form.job_title = props.contact.job_title
        form.company = props.contact.company
        form.country = props.contact.country
        form.country_code = props.contact.country_code
        form.country_fly = props.contact.country_fly 
        fetchCountries()
    })
</script>

<style scoped>
</style>
