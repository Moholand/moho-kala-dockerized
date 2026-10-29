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
                            <div class="address-detail" v-if="defaultAddress">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ defaultAddress }}</span>
                            </div>
                            <div class="address-empty" v-else>
                                هیچ آدرسی موجود نیست!
                            </div>
                        </div>
                        <button class="change-address-btn" @click.prevent>تغییر آدرس</button>
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
    </div>
</template>

<script>
import CartItem from "./CartItem.vue";
import ContinueCheckout from "./ContinueCheckout.vue";
import alertMixin from '@/helpers/mixins/alertMixin';

export default {
    components: { CartItem, ContinueCheckout },
    data() {
        return {
            cartItems: null,
            defaultAddress: 'تهران، خیابان ولیعصر، پلاک ۱۲، کد پستی ۱۲۳۴۵۶۷۸۹۰',
        }
    },
    mixins: [alertMixin],
    created() {
        axios.get(`/api/carts`)
            .then(response => this.cartItems = response.data)
            .catch((error) => console.log(error))
    },
    methods: {
        deleteItem(id) {
            this.cartItems = this.cartItems.filter(item => item.id !== id);
        }
    },
    computed: {
        itemsCount() {
            return this.cartItems.reduce((count, item) => count + item.pivot.count, 0);
        },
        totalPrice() {
            return this.cartItems.reduce((price, item) => price + (item.pivot.count * item.price), 0);
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
    .items-count {
        font-size: 14px;
        color: #81858b;
        padding-right: 24px;
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
</style>
