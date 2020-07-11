import {mapActions} from 'vuex'
import middleware from './middleware'
export default {
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,

        nama: '',
        ktp: '',
        namaRules: [
          v => !!v || 'Tidak Boleh Kosong',
        ],
        ktpRules: [
            v => !!v || 'Tidak Boleh Kosong',
            v => v.length <= 15 || 'Maksimal panjang 15',
            v => /^\d+$/.test(v) || 'Harus angka'
          ],

        alamat: '',
        alamatRules: [
          v => !!v || 'Tidak Boleh Kosong',
        ],
      }),
    methods: {
        ...mapActions({
            setSnakbar: 'snakbar/setSnakbar'
        }),


    },

    mixins:[middleware],

    created(){

    }
}
