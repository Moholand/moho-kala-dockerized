<template>
    <div class="continue-checkout">
        <div class="price-info d-flex justify-content-between pt-3">
            <div class="price-label">
                <span class="px-1">قیمت کالاها</span>
                <span>({{ itemsCount }})</span>
            </div>
            <div class="price-label">
                <span class="px-1">{{ new Intl.NumberFormat().format(totalPrice) }}</span>
                <span>تومان</span>
            </div>
        </div>
        <a class="continue-checkout-btn" @click.prevent="handleClick">{{ buttonText }}</a>
    </div>
</template>

<script>
export default {
    props: {
        itemsCount: Number,
        totalPrice: Number
    },
    computed: {
        buttonText() {
            switch (this.$route.name) {
                case 'cart':
                    return 'تایید و تکمیل سفارش';
                case 'shipping':
                    return 'ثبت سفارش';
                default:
                    return '';
            }
        }
    },
    methods: {
        handleClick() {
            if (this.$route.name === 'cart') {
                this.$router.push({ name: 'shipping' });
            }
        }
    }
}
</script>

<style scoped>
    .price-info {
        font-size: 12px;
        color: #62666d;
    }
    .continue-checkout-btn {
        display: flex;
        justify-content: center;
        background-color: #ef4056;
        color: #ffffff;
        padding: 12px 16px;
        margin: 16px 0;
        text-decoration: none;
        font-size: 14px;
        border-radius: 10px;
        cursor: pointer;
    }
    .continue-checkout-btn:hover {
        color: #ffffff;
    }
</style>
