<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-6 mx-auto mt-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Ajout de nouvel contact
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="addNewContact">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom*</label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            v-model="form.name"
                                            id="name"
                                            class="form-control"
                                            placeholder="Nom"    
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
        @change="onCountryChange"
        id="country"
        class="form-select"
    >
        <option disabled value="">Select a country</option>
        <option 
            v-for="c in countries" 
            :key="c.code" 
            :value="c.name"
        >
            {{ c.name }} {{ c.flag }}
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
                                <button type="submit"
                                    class="btn btn-sm btn-dark me-1"
                                >
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
    import isoCountries from "i18n-iso-countries" // Renommer ici pour éviter la collision
    import enLocale from "i18n-iso-countries/langs/en.json"
    isoCountries.registerLocale(enLocale)
    import { ref, onMounted } from 'vue'
    import axios from 'axios'

    // Déclare un ref pour stocker les pays de l'API
    const countries = ref([])

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

            countries.value = sorted
        } catch (error) {
            console.error("Erreur lors de la récupération des pays :", error)
        }
    }

    // Quand l’utilisateur choisit un pays
    const onCountryChange = (e) => {
        const selected = countries.value.find(c => c.name === e.target.value)
        form.country = selected?.name || ''
        form.country_code = selected?.code || ''
        form.country_fly = selected?.flag || ''
    }

    //define the data object
    const form = useForm({
        name: '',
        email: '',
        phone: '',
        job_title: '',
        company: '',
        country: '',
        country_code: '',
        country_fly: ''
    })

    // Soumission du formulaire
    const addNewContact = () => {
        form.post(route('contacts.store'))
    }

    // Appel au montage
    onMounted(fetchCountries)

    //set the country alpha code 
    const setCountryAlphaCode = (e) => {
        form.country_code = isoCountries.getAlpha2Code(e.target.value,"en")
    }
</script>


<style scoped>
</style>