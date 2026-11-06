<template>
    <div class="cart">
        <alert :alertData="alertData"></alert>
        <div class="cart-full d-flex" v-if="cartItems">
            <div class="items-wrapper w-75">
                <div class="items-header">آدرس و زمان ارسال</div>
                <div class="items-body">
                    <div class="address-box d-flex justify-content-between align-items-center">
                        <div class="address-info">
                            <div class="address-heading">ارسال به آدرس انتخاب‌ شده</div>
                            <div class="address-detail" v-if="addressText">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ addressText }}</span>
                            </div>
                            <div class="address-empty" v-else>
                                هیچ آدرسی موجود نیست!
                            </div>
                        </div>
                        <button class="change-address-btn" @click.prevent="openAddressModal">تغییر آدرس</button>
                    </div>
                </div>
            </div>
            <div class="continue-checkout-wrapper w-25">
                <ContinueCheckout 
                    :itemsCount="itemsCount" 
                    :totalPrice="totalPrice"
                />
            </div>
        </div>
        <div class="cart-empty d-flex align-items-center flex-column" v-else>
            <img src="/images/logos/cart/empty-cart.svg" width="200" height="150" alt="empty-cart"/>
            <p>سبد خرید شما خالی است!</p>
        </div>

        <Teleport to="body">
            <div
                class="address-modal-overlay"
                v-if="isAddressModalOpen"
                @click.self="closeAddressModal"
            >
                <div class="address-modal">
                    <div class="address-modal-header d-flex justify-content-between align-items-center">
                        <span class="address-modal-title">انتخاب آدرس</span>
                        <button type="button" class="address-modal-close" @click="closeAddressModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="address-modal-body">
                        <div class="address-modal-loading" v-if="addressesLoading">
                            در حال دریافت آدرس‌ها...
                        </div>
                        <div class="address-list" v-else-if="sortedAddresses.length">
                            <button
                                type="button"
                                class="address-item"
                                v-for="address in sortedAddresses"
                                :key="address.id"
                                :class="{
                                    'is-default': address.is_default,
                                    'is-selected': selectedAddress && selectedAddress.id === address.id
                                }"
                                @click="selectAddress(address)"
                            >
                                <i class="fa-solid fa-location-dot address-item-icon"></i>
                                <div class="address-item-info">
                                    <div class="address-item-heading d-flex align-items-center">
                                        <span class="address-item-label" v-if="address.label">
                                            {{ address.label }}
                                        </span>
                                        <span class="address-item-default-badge" v-if="address.is_default">
                                            پیش‌فرض
                                        </span>
                                    </div>
                                    <div class="address-item-text">{{ formatAddress(address) }}</div>
                                    <div class="address-item-phone" v-if="address.phone">
                                        {{ address.phone }}
                                    </div>
                                </div>
                                <i
                                    class="fa-solid fa-circle-check address-item-check"
                                    v-if="selectedAddress && selectedAddress.id === address.id"
                                ></i>
                            </button>
                        </div>
                        <div class="address-modal-empty" v-else>
                            هیچ آدرسی ثبت نکرده‌اید!
                        </div>
                    </div>

                    <div class="address-modal-footer">
                        <button type="button" class="add-address-btn">افزودن آدرس جدید</button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
import ContinueCheckout from "./ContinueCheckout.vue";
import alertMixin from '@/helpers/mixins/alertMixin';

export default {
    components: { ContinueCheckout },
    data() {
        return {
            cartItems: null,
            defaultAddress: null,
            isAddressModalOpen: false,
            addresses: [],
            addressesLoading: false,
            selectedAddress: null,
        }
    },
    mixins: [alertMixin],
    created() {
        axios.get(`/api/carts`)
            .then(response => this.cartItems = response.data)
            .catch((error) => console.log(error))

        this.fetchDefaultAddress();
    },
    methods: {
        fetchDefaultAddress() {
            axios.get(`/api/addresses/default`)
                .then(response => {
                    this.defaultAddress = response.data.address;
                    this.selectedAddress = response.data.address;
                })
                .catch((error) => console.log(error))
        },
        openAddressModal() {
            this.isAddressModalOpen = true;
            this.fetchAddresses();
        },
        closeAddressModal() {
            this.isAddressModalOpen = false;
        },
        fetchAddresses() {
            this.addressesLoading = true;

            axios.get(`/api/addresses`)
                .then(response => this.addresses = response.data.addresses)
                .catch((error) => console.log(error))
                .finally(() => this.addressesLoading = false);
        },
        selectAddress(address) {
            this.selectedAddress = address;
            this.isAddressModalOpen = false;
        },
        formatAddress(address) {
            if (!address) {
                return null;
            }

            const parts = [];
            const location = address.location;

            if (location) {
                if (location.parent) {
                    parts.push(location.parent.name);
                }
                parts.push(location.name);
            }

            if (address.street) {
                parts.push(address.street);
            }

            if (address.postal_code) {
                parts.push(`کد پستی ${address.postal_code}`);
            }

            return parts.join('، ');
        },
    },
    computed: {
        itemsCount() {
            return this.cartItems.reduce((count, item) => count + item.pivot.count, 0);
        },
        totalPrice() {
            return this.cartItems.reduce((price, item) => price + (item.pivot.count * item.price), 0);
        },
        sortedAddresses() {
            return [...this.addresses].sort((first, second) => {
                return Number(second.is_default) - Number(first.is_default);
            });
        },
        addressText() {
            return this.formatAddress(this.selectedAddress || this.defaultAddress);
        },
    },
}
</script>

<style>
    .cart {
        padding: 75px 300px 50px 300px;
    }
    .items-wrapper {
        margin-left: 20px;
        border: 1px solid #e0e0e2;
        border-radius: 10px;
        padding: 12px 0;
    }
    .items-header {
        font-weight: 500;
        margin-bottom: 5px;
        padding-right: 24px;
    }
    .continue-checkout-wrapper {
        border: 1px solid #e0e0e2;
        border-radius: 10px;
        padding: 12px 20px;
        height: max-content;
    }
    .cart-empty {
        border: 1px solid #e0e0e2;
        border-radius: 10px;
        padding: 20px 0 50px 0;
    }
    .cart-empty p {
        margin-top: 24px;
        font-size: 19px;
        font-weight: 500;
    }
    .address-box {
        margin: 0 24px;
        padding: 16px 20px;
        border: 1px solid #e0e0e2;
        border-radius: 10px;
    }
    .address-heading {
        font-weight: 500;
        margin-bottom: 8px;
    }
    .address-detail {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #62666d;
    }
    .address-detail i {
        color: #ef4056;
        margin-left: 8px;
    }
    .address-empty {
        font-size: 13px;
        color: #ef4056;
    }
    .change-address-btn {
        background-color: transparent;
        border: 1px solid #ef4056;
        color: #ef4056;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        cursor: pointer;
        white-space: nowrap;
    }
    .address-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .address-modal {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 480px;
        max-height: 85vh;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        direction: rtl;
    }
    .address-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e0e0e2;
    }
    .address-modal-title {
        font-size: 16px;
        font-weight: 600;
    }
    .address-modal-close {
        background-color: transparent;
        border: 0;
        color: #62666d;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        padding: 0;
    }
    .address-modal-close:hover {
        color: #ef4056;
    }
    .address-modal-body {
        flex: 1;
        padding: 16px 20px;
        overflow-y: auto;
    }
    .address-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .address-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        padding: 12px 14px;
        background-color: #ffffff;
        border: 1px solid #e0e0e2;
        border-radius: 10px;
        text-align: right;
        font-family: inherit;
        cursor: pointer;
    }
    .address-item:hover {
        border-color: #ef4056;
    }
    .address-item.is-default {
        border: 2px solid #ef4056;
    }
    .address-item.is-selected {
        background-color: #fff5f6;
    }
    .address-item-icon {
        color: #ef4056;
        margin-top: 3px;
    }
    .address-item-info {
        flex: 1;
    }
    .address-item-heading {
        gap: 8px;
        margin-bottom: 6px;
    }
    .address-item-label {
        font-size: 13px;
        font-weight: 500;
    }
    .address-item-default-badge {
        background-color: #ef4056;
        color: #ffffff;
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 4px;
    }
    .address-item-text {
        font-size: 13px;
        color: #62666d;
        line-height: 1.8;
    }
    .address-item-phone {
        margin-top: 4px;
        font-size: 12px;
        color: #81858b;
    }
    .address-item-check {
        color: #ef4056;
        font-size: 16px;
        margin-top: 3px;
    }
    .address-modal-loading,
    .address-modal-empty {
        padding: 24px 0;
        font-size: 13px;
        color: #81858b;
        text-align: center;
    }
    .address-modal-footer {
        padding: 14px 20px;
        border-top: 1px solid #e0e0e2;
    }
    .add-address-btn {
        width: 100%;
        background-color: #ffffff;
        border: 1px solid #ef4056;
        color: #ef4056;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        cursor: pointer;
    }
    .add-address-btn:hover {
        background-color: #fff5f6;
    }
</style>
