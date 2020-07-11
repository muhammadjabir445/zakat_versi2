import {mapActions} from 'vuex'
import middleware from './middleware'
export default {
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,
        select:'',
        items:[],
        email:'',
        nohp:'',
        emailRules: [
            v => /.+@.+\..+/.test(v) || 'E-mail tidak valid',
            v => !!v || 'Tidak Boleh Kosong',
        ],
        nohpRules: [
            v => !!v || 'Tidak Boleh Kosong',
            v => v.length <= 13 || 'Maksimal panjang 13',
            v => /^\d+$/.test(v) || 'Harus angka'
        ],

        nama: '',
        namaRules: [
          v => !!v || 'Tidak Boleh Kosong',
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
