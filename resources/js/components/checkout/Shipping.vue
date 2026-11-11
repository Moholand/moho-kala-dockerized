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
                        <span class="address-modal-title">
                            {{ isAddingAddress ? 'افزودن آدرس جدید' : 'انتخاب آدرس' }}
                        </span>
                        <button type="button" class="address-modal-close" @click="closeAddressModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="address-modal-body">
                        <form
                            id="address-form"
                            class="address-form"
                            v-if="isAddingAddress"
                            @submit.prevent="storeAddress"
                        >
                            <div class="address-form-field">
                                <label for="address-label">عنوان آدرس</label>
                                <input
                                    id="address-label"
                                    type="text"
                                    v-model="addressForm.label"
                                    placeholder="مثلاً خانه"
                                    maxlength="50"
                                >
                                <span
                                    class="address-form-error"
                                    v-if="addressFormErrors && addressFormErrors.label"
                                >{{ addressFormErrors.label[0] }}</span>
                            </div>

                            <div class="address-form-row">
                                <div class="address-form-field">
                                    <label for="address-province">استان</label>
                                    <select
                                        id="address-province"
                                        v-model="addressForm.provinceId"
                                        :disabled="locationsLoading"
                                        required
                                        @change="addressForm.cityId = ''"
                                    >
                                        <option value="">انتخاب استان</option>
                                        <option
                                            v-for="province in provinces"
                                            :key="province.id"
                                            :value="province.id"
                                        >{{ province.name }}</option>
                                    </select>
                                </div>

                                <div class="address-form-field">
                                    <label for="address-city">شهر</label>
                                    <select
                                        id="address-city"
                                        v-model="addressForm.cityId"
                                        :disabled="locationsLoading || !addressForm.provinceId"
                                        required
                                    >
                                        <option value="">انتخاب شهر</option>
                                        <option
                                            v-for="city in cities"
                                            :key="city.id"
                                            :value="city.id"
                                        >{{ city.name }}</option>
                                    </select>
                                    <span
                                        class="address-form-error"
                                        v-if="addressFormErrors && addressFormErrors.location_id"
                                    >{{ addressFormErrors.location_id[0] }}</span>
                                </div>
                            </div>

                            <div class="address-form-field">
                                <label for="address-street">نشانی</label>
                                <textarea
                                    id="address-street"
                                    v-model="addressForm.street"
                                    rows="2"
                                    required
                                ></textarea>
                                <span
                                    class="address-form-error"
                                    v-if="addressFormErrors && addressFormErrors.street"
                                >{{ addressFormErrors.street[0] }}</span>
                            </div>

                            <div class="address-form-row">
                                <div class="address-form-field">
                                    <label for="address-postal-code">کد پستی</label>
                                    <input
                                        id="address-postal-code"
                                        type="text"
                                        v-model="addressForm.postal_code"
                                        inputmode="numeric"
                                        maxlength="20"
                                        required
                                    >
                                    <span
                                        class="address-form-error"
                                        v-if="addressFormErrors && addressFormErrors.postal_code"
                                    >{{ addressFormErrors.postal_code[0] }}</span>
                                </div>

                                <div class="address-form-field">
                                    <label for="address-phone">شماره تماس</label>
                                    <input
                                        id="address-phone"
                                        type="tel"
                                        v-model="addressForm.phone"
                                        maxlength="20"
                                    >
                                    <span
                                        class="address-form-error"
                                        v-if="addressFormErrors && addressFormErrors.phone"
                                    >{{ addressFormErrors.phone[0] }}</span>
                                </div>
                            </div>

                            <label class="address-form-default">
                                <input type="checkbox" v-model="addressForm.is_default">
                                <span>این آدرس به عنوان آدرس پیش‌فرض ذخیره شود</span>
                            </label>
                        </form>

                        <template v-else>
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
                        </template>
                    </div>

                    <div class="address-modal-footer">
                        <div class="address-modal-actions" v-if="isAddingAddress">
                            <button type="button" class="add-address-btn" @click="cancelAddressForm">
                                بازگشت
                            </button>
                            <button
                                type="submit"
                                form="address-form"
                                class="submit-address-btn"
                                :disabled="addressSaving"
                            >
                                {{ addressSaving ? 'در حال ثبت...' : 'ثبت آدرس' }}
                            </button>
                        </div>
                        <button
                            type="button"
                            class="add-address-btn"
                            v-else
                            @click="openAddressForm"
                        >افزودن آدرس جدید</button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
import ContinueCheckout from "./ContinueCheckout.vue";
import alertMixin from '@/helpers/mixins/alertMixin';

const createEmptyAddressForm = () => ({
    label: '',
    provinceId: '',
    cityId: '',
    street: '',
    postal_code: '',
    phone: '',
    is_default: false,
});

export default {
    components: { ContinueCheckout },
    data() {
        return {
            cartItems: null,
            defaultAddress: null,
            isAddressModalOpen: false,
            isAddingAddress: false,
            addresses: [],
            addressesLoading: false,
            selectedAddress: null,
            addressForm: createEmptyAddressForm(),
            addressFormErrors: null,
            addressSaving: false,
            locations: [],
            locationsLoading: false,
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
            this.isAddingAddress = false;
            this.addressFormErrors = null;
            this.fetchAddresses();
        },
        closeAddressModal() {
            this.isAddressModalOpen = false;
            this.isAddingAddress = false;
            this.addressFormErrors = null;
        },
        openAddressForm() {
            this.addressForm = createEmptyAddressForm();
            this.addressFormErrors = null;
            this.isAddingAddress = true;
            this.fetchLocations();
        },
        cancelAddressForm() {
            this.isAddingAddress = false;
            this.addressFormErrors = null;
        },
        fetchAddresses() {
            this.addressesLoading = true;

            axios.get(`/api/addresses`)
                .then(response => this.addresses = response.data.addresses)
                .catch((error) => console.log(error))
                .finally(() => this.addressesLoading = false);
        },
        fetchLocations() {
            if (this.locations.length) {
                return;
            }

            this.locationsLoading = true;

            axios.get(`/api/locations`)
                .then(response => this.locations = response.data.locations)
                .catch((error) => console.log(error))
                .finally(() => this.locationsLoading = false);
        },
        storeAddress() {
            if (this.addressSaving) {
                return;
            }

            this.addressFormErrors = null;
            this.addressSaving = true;

            const payload = {
                label: this.addressForm.label || null,
                phone: this.addressForm.phone || null,
                street: this.addressForm.street,
                postal_code: this.addressForm.postal_code,
                location_id: this.addressForm.cityId || null,
                is_default: this.addressForm.is_default,
            };

            axios.post(`/api/addresses`, payload)
                .then(({ data }) => {
                    const address = data.address;

                    this.addresses.push(address);

                    if (address.is_default) {
                        this.addresses = this.addresses.map(item => ({
                            ...item,
                            is_default: item.id === address.id,
                        }));
                        this.defaultAddress = address;
                    }

                    this.selectedAddress = address;
                    this.isAddingAddress = false;
                    this.addressForm = createEmptyAddressForm();
                    this.showAlert('آدرس با موفقیت ثبت شد', 'success');
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.addressFormErrors = error.response.data.errors;
                    } else {
                        this.showAlert('خطا در ثبت آدرس', 'error');
                    }
                })
                .finally(() => this.addressSaving = false);
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
        provinces() {
            return this.locations.filter(location => location.type === 'province');
        },
        cities() {
            return this.locations.filter(location => {
                return location.type === 'city'
                    && String(location.parent_id) === String(this.addressForm.provinceId);
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
    .address-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .address-form-row {
        display: flex;
        gap: 12px;
    }
    .address-form-row .address-form-field {
        flex: 1;
    }
    .address-form-field {
        display: flex;
        flex-direction: column;
    }
    .address-form-field label {
        margin-bottom: 4px;
        font-size: 12px;
        color: #3f4064;
    }
    .address-form-field input,
    .address-form-field select,
    .address-form-field textarea {
        width: 100%;
        padding: 8px 10px;
        background-color: #ffffff;
        border: 1px solid #e0e0e2;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        color: #3f4064;
    }
    .address-form-field input:focus,
    .address-form-field select:focus,
    .address-form-field textarea:focus {
        outline: none;
        border-color: #ef4056;
    }
    .address-form-field textarea {
        resize: vertical;
    }
    .address-form-error {
        margin-top: 4px;
        font-size: 11px;
        color: #ef4056;
    }
    .address-form-default {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #3f4064;
        cursor: pointer;
    }
    .address-form-default input {
        accent-color: #ef4056;
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
    .address-modal-actions {
        display: flex;
        gap: 10px;
    }
    .address-modal-actions .add-address-btn,
    .address-modal-actions .submit-address-btn {
        flex: 1;
    }
    .submit-address-btn {
        background-color: #ef4056;
        border: 1px solid #ef4056;
        color: #ffffff;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        cursor: pointer;
    }
    .submit-address-btn:hover {
        background-color: #d9364a;
    }
    .submit-address-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
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
