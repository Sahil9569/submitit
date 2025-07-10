<template>
    <div>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <h1 class="navbar-brand-box">
                        <div class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="@/assets/images/logo-header.png" alt height="36" />
                            </span>
                            <span class="logo-lg">
                                <img src="@/assets/images/logo-header.png" alt height="30" />
                            </span>
                        </div>

                        <div class="logo logo-light">
                            <span class="logo-sm">
                                <img src="@/assets/images/logo-header-light.png" alt height="36" />
                            </span>
                            <span class="logo-lg">
                                <img src="@/assets/images/logo-header-light.png" alt height="30" />
                            </span>
                        </div>
                    </h1>
                </div>
            </div>
        </header>
    </div>
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content mt-5 mb-4">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title">Payment information</h4>
                                    <p class="card-title-desc">Please fill out all information below</p>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check form-check-inline font-size-16">
                                        <h4 class="font-size-13"><i
                                                class="fab fa-cc-mastercard me-1 font-size-20 align-top"></i> Credit / Debit
                                            Card</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border">
                                <form>
                                    <div class="form-group mb-0">
                                        <label for="cardnumberInput">Card Number</label>
                                        <div type="text" class="form-control validateInput" id="example2-card-number"
                                            placeholder="0000 0000 0000 0000"
                                            :class="{ 'is-invalid': this.addPaymentStatusError.code == 'incomplete_number' || this.addPaymentStatusError.code == 'invalid_number' }">
                                        </div>
                                    </div>
                                    <span class="text-danger"
                                        v-if="this.addPaymentStatusError.code == 'incomplete_number' || this.addPaymentStatusError.code == 'invalid_number'">{{
                                            this.addPaymentStatusError.message }}</span>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mt-4 mb-0">
                                                <label for="cardnameInput">Name on card</label>
                                                <input type="text" v-model="cardHolderName"
                                                    class="form-control validateInput" id="cardnameInput"
                                                    placeholder="Name on Card">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mt-4 mb-0">
                                                <label for="expirydateInput">Expiration
                                                    date</label>
                                                <div type="text" class="form-control validateInput"
                                                    id="example2-card-expiry" placeholder="MM/YY"
                                                    :class="{ 'is-invalid': this.addPaymentStatusError.code == 'incomplete_expiry' || this.addPaymentStatusError.code == 'invalid_expiry_year_past' }">
                                                </div>
                                            </div>
                                            <span class="text-danger"
                                                v-if="this.addPaymentStatusError.code == 'incomplete_expiry' || this.addPaymentStatusError.code == 'invalid_expiry_year_past'">{{
                                                    this.addPaymentStatusError.message }}</span>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mt-4 mb-0">
                                                <label for="cvvcodeInput">CVV Code</label>
                                                <div type="text" id="example2-card-cvc" class="form-control validateInput"
                                                    placeholder="Enter CVV Code"
                                                    :class="{ 'is-invalid': this.addPaymentStatusError.code == 'incomplete_cvc' }">
                                                </div>
                                            </div>
                                            <span class="text-danger"
                                                v-if="this.addPaymentStatusError.code == 'incomplete_cvc'">{{
                                                    this.addPaymentStatusError.message }}</span>
                                        </div>

                                    </div>
                                    <div class="mt-4 pt-3 text-end">
                                        <button type="button" @click="submitPaymentMethod" class="btn btn-theme">
                                            <span class="spinner-border spinner-border-sm " v-if="spinner" role="status"
                                                aria-hidden="true"></span>
                                            Proceed to Checkout</button>
                                    </div>

                                </form>
                                <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
                <Footer />
            </div>
        </div>
    </div>
</template>
<script>
// import PageHeader from "@/components/admin/header/page-header";
// import HorizontalTopbar from "@/components/admin/header/horizontal-topbar";
import Footer from "@/components/admin/footer/footer";
import axios from "axios";
import CryptoJS from 'crypto-js';

export default ({
    components: {
        // PageHeader,
        // HorizontalTopbar,
        Footer
    },
    data() {
        return {
            url: process.env.VUE_APP_URL,
            stripeAPIToken: 'pk_test_51MzHpCSGmK7KROFRYQnRH4sYZeICmsQsn6zoVJNNgWzgWruP2XDhhpeq9lt5jRpiujZ2Y00UaWHlBgiTUDFnrdDV00bIcMP4ie',
            stripe: '',
            elements: null,
            card: '',
            clientSecret: "",
            intentToken: null,
            addPaymentStatusError: '',
            cardElement: null,
            cardHolderName: null,
            zipCode: null,
            spinner: false,
            selectedPackages: [],
            email: null,
            extraService: false
        };
    },
    mounted() {
        this.getPackagesData()
    },
    methods: {
        includeStripe(URL, callback) {
            let documentTag = document, tag = 'script',
                object = documentTag.createElement(tag),
                scriptTag = documentTag.getElementsByTagName(tag)[0];
            object.src = '//' + URL;
            if (callback) { object.addEventListener('load', function (e) { callback(null, e); }, false); }
            scriptTag.parentNode.insertBefore(object, scriptTag);
        },
        configureStripe() {
            this.stripe = window.Stripe(this.stripeAPIToken);
            const appearance = {
                theme: 'stripe',
            }
            let clientSecret = this.clientSecret;
            this.elements = this.stripe.elements({ clientSecret, appearance });
            var inputs = document.querySelectorAll('.validateInput');
            Array.prototype.forEach.call(inputs, function (input) {
                input.addEventListener('focus', function () {
                    input.classList.add('focused');
                });
                input.addEventListener('blur', function () {
                    input.classList.remove('focused');
                });
                input.addEventListener('keyup', function () {
                    if (input.value.length === 0) {
                        input.classList.add('empty');
                    } else {
                        input.classList.remove('empty');
                    }
                });
            });

            var elementStyles = {
                base: {
                    color: '#32325D',
                    fontWeight: 500,
                    fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
                    fontSize: '16px',
                    fontSmoothing: 'antialiased',

                    '::placeholder': {
                        color: '#CFD7DF',
                    },
                    ':-webkit-autofill': {
                        color: '#e39f48',
                    },
                },
                invalid: {
                    color: '#E25950',

                    '::placeholder': {
                        color: '#FFCCA5',
                    },
                },
            };

            var elementClasses = {
                focus: 'focused',
                empty: 'empty',
                invalid: 'invalid',
            };

            this.cardElement = this.elements.create('cardNumber', {
                style: elementStyles,
                classes: elementClasses,
            });
            this.cardElement.mount('#example2-card-number');

            this.cardElement = this.elements.create('cardExpiry', {
                style: elementStyles,
                classes: elementClasses,
            });
            this.cardElement.mount('#example2-card-expiry');

            this.cardElement = this.elements.create('cardCvc', {
                style: elementStyles,
                classes: elementClasses,
            });
            this.cardElement.mount('#example2-card-cvc');

            this.cardElement.addEventListener("change", function (event) {
                // if (event.empty) {
                document.querySelector("button").disabled = event.empty;

                // }
            });
        },
        loadIntent() {
            axios.get(this.url + 'api/setup-intent').then(res => {
                this.intentToken = res.data;
                this.clientSecret = res.data.client_secret;
                this.includeStripe('js.stripe.com/v3/', function () {
                    this.configureStripe();
                }.bind(this));
            })
        },
        loadPaymentIntent() {
            axios.post(this.url + 'api/extraService', { serviceToken: this.$route.params.id, id: atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))) }).then(res => {
                this.intentToken = res.data
                this.clientSecret = res.data.paymentIntent.client_secret
                this.includeStripe('js.stripe.com/v3/', function () {
                    this.configureStripe();
                }.bind(this));
            })
        },
        submitPaymentMethod() {
            if (!this.extraService) {
                this.stripe.confirmCardSetup(
                    this.clientSecret, {
                    payment_method: {
                        card: this.cardElement,
                        billing_details: {
                            name: this.cardHolderName
                        }
                    }
                }
                ).then(function (result) {
                    if (result.error) {
                        this.addPaymentStatusError = result.error;
                    } else {
                        this.createSubscription(result.setupIntent.payment_method);
                        this.cardElement.clear();
                        this.name = '';
                    }
                }.bind(this));
            }
            else {
                this.doPayment()
            }

        },
        getPackagesData() {
            axios.post(this.url + 'api/getSelectedPackages', { link: this.$route.params.id }).then(res => {
                if (res.data.length === 0) {
                    this.extraService = true
                    this.loadPaymentIntent()
                }
                else if (res.data.status == 'Success') {
                    alert('Sorry! You cannot do the payment now')
                    this.$router.push('/')
                }
                else {
                    this.loadIntent()
                    this.selectedPackages = res.data.packages.split(',')
                    this.email = res.data.email
                }
            })
        },
        createSubscription(paymentId) {
            this.spinner = true;
            axios.post(this.url + 'api/createSubscription', { 'email': this.email, 'paymentLink': this.$route.params.id, 'plans': this.selectedPackages, 'paymentId': paymentId }).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Payment Successfull'
                this.$router.push('/')
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        },
        async doPayment() {
            const { paymentIntent, error } = await this.stripe.confirmCardPayment(this.clientSecret, {
                payment_method: {
                    card: this.cardElement,
                }
            });

            if (error) {
                console.error(error.message);
            }
            else {
                const response = await axios.post(this.url + 'api/confirm-payment-intent', { payment_intent_id: paymentIntent.id, token: this.$route.params.id, id: atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))) });

                if (response.data.success) {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Payment Successfull'
                    this.$router.push('/')
                    setTimeout(()=>{
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                } else {
                    console.error(response.data.error);
                }
            }
        },
        decode(id) {
            return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
        },
    }
})
</script>
<style>
.example.example2 {
    background-color: #fff;
}

.example.example2 .input.focused+label {
    color: #24b47e;
}

.example.example2 .input.invalid+label {
    color: #e21313;
}

.example.example2 .input.focused+label+.baseline {
    background-color: #24b47e;
}

.example.example2 .input.focused.invalid+label+.baseline {
    background-color: #d32115;
}

.example.example2 input,


.example.example2 input:-webkit-autofill {
    -webkit-text-fill-color: #b10606;
    transition: background-color 100000000s;
    -webkit-animation: 1ms void-animation-out;
}

.example.example2 .StripeElement--webkit-autofill {
    background: transparent !important;
}

.example.example2 .error svg {
    margin-top: 0 !important;
}

.example.example2 .error svg .base {
    fill: #ee1f10;
}

.example.example2 .error svg .glyph {
    fill: #fff;
}

.example.example2 .error .message {
    color: #dd2619;
}
</style>