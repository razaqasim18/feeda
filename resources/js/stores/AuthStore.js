import axios from "axios";
import { defineStore } from "pinia";
import { useBasketStore } from "./BasketStore";
import { useToast } from "vue-toastification";
import { useMaster } from "./MasterStore";
import ToastSuccessMessage from "../components/ToastSuccessMessage.vue"
const toast = useToast();


export const useAuth = defineStore("authStore", {
    state: () => ({
        user: null,
        addresses: [],
        pointreedems: [],
        coupons: [],
        token: null,
        favoriteProducts: 0,
        loginModal: false,
        registerModal: false,
        showAddressModal: false,
        showChangeAddressModal: false,
        orderCancel: false,
        favoriteRemove: false,
        userpoint: 0,
    }),

    getters: {
        getAddressById: (state) => (id) => {
            return state.addresses.find((address) => address.id == id);
        },
    },

    actions: {
        setToken(token) {
            this.token = `Bearer ${token}`;
        },
        setUser(user) {
            this.user = user;
        },

        showLoginModal() {
            this.loginModal = true;
        },

        hideLoginModal() {
            this.loginModal = false;
        },

        fetchAddresses() {
            axios.get("/addresses", {
                headers: {
                    Authorization: this.token,
                },
            }).then((response) => {
                this.addresses = response.data.data.addresses;
                const basketStore = useBasketStore();
                this.addresses.forEach((address) => {
                    if (address.is_default) {
                        basketStore.address = address;
                        return true;
                    } else {
                        basketStore.address = this.addresses[0];
                    }
                });
            })
                .catch((error) => {
                    if (error.response.status === 401) {
                        this.token = null;
                        this.user = null;
                        this.addresses = [];
                        this.favoriteProducts = 0;
                    }
                });
        },

        fetchPointRedeemlist() {
            axios.get("/point-redeem-list", {
                headers: {
                    userid: this.user.id,
                    Authorization: this.token,
                },
            }).then((response) => {
                this.pointreedems = response.data.data.pointredeem;
                this.userpoint = response.data.data.userpoint;
            })
                .catch((error) => {
                    // this.token = null;
                    // this.user = null;
                    // this.pointreedems = [];
                    // this.userpoint = 0;
                });
        },

        fetchPointRedeemcouponList(page = 1, perPage = 10) {
            axios.get("/point-redeem-user-list", {
                params: {
                    page: page,
                    per_page: perPage,
                },
                headers: {
                    userid: this.user.id,
                    Authorization: this.token,
                },
            })
                .then((response) => {
                    // Laravel paginator response
                    this.coupons = response.data.data.pointcoupons.data;
                    this.totalItems = response.data.data.pointcoupons.total;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        redeemPoints(id) {
            const masterStore = useMaster();
            return axios.post(
                "/point-redeem",
                {
                    userid: this.user.id,
                    pointredeem_id: id,
                },
                {
                    headers: {
                        Authorization: this.token,
                    },
                }
            )
                .then((response) => {
                    const message = response.data.message || "Points redeemed successfully";
                    const content = {
                        component: ToastSuccessMessage,
                        props: {
                            title: 'Coupon Created',
                            message: message,
                        }
                    };

                    toast(content, {
                        type: "default",
                        hideProgressBar: true,
                        icon: false,
                        position: masterStore.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
                        toastClassName: "vue-toastification-alert",
                        timeout: 3000,
                    });

                    return response; // ✅ important
                });
        },

        fetchFavoriteProducts() {
            if (this.token) {
                axios.get("/favorite-products", {
                    headers: {
                        Authorization: this.token,
                    },
                }).then((response) => {
                    this.favoriteProducts = response.data.data.products?.length ?? 0;
                }).catch((error) => {
                    if (error.response.status === 401) {
                        this.token = null;
                        this.user = null;
                        this.addresses = [];
                    }
                });
            } else {
                this.favoriteProducts = 0;
            }
        },

        logout() {
            axios.get("/logout", {
                headers: {
                    Authorization: this.token,
                },
            }).then((response) => {
                this.user = null;
                this.addresses = [];
                this.token = null;
                this.favoriteProducts = 0;
            }).catch((error) => {
                this.user = null;
                this.addresses = [];
                this.token = null;
                this.favoriteProducts = 0;
            });
        },
    },

    persist: true,
});
