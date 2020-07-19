import {mapActions} from 'vuex'
import middleware from './middleware'
export default {
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,
        jenis_pengajuan:'',
        total_uang_sisa : 0,
        total_uang_beras : 0,
        total_beras_sisa : 0,
        pilihan:['Pembagian zakat','Pembelian beras'],
        beras: 0,
        berasRules: [
            v => /^\d+$/.test(v) || 'Harus angka',
        ],
        berasValidasi(sisa_beras,jenis_pengajuan){
            if (jenis_pengajuan == 'Pembagian zakat') {
                    return v => v <= sisa_beras || 'Beras Tidak Cukup'
            }
        },
        uangValidasi(total_uang_beras,total_uang_sisa,jenis_pengajuan){
            if (jenis_pengajuan == 'Pembagian zakat') {
                return v => v <= total_uang_sisa || 'Uang tidak cukup'
            } else if(jenis_pengajuan == 'Pembelian Beras'){
                return v => v <= total_uang_beras || 'Uang tidak cukup'

            }
        },
        nama_yayasan : '',
        lembaga:[],
        uang:0,
        deskripsi:'',
        harga_beras:'',
        uangRules:[
            v => !!v || 'Harus diisi',
          //   v => /^\d*\.?(?:\d{1,2})?$/.test(v) || 'digit cuma 2'
            v => /^\d*\.?(?:\d{1,1000000000})?$/.test(v) || 'Format Salah'
          ],
      }),
    methods: {
        ...mapActions({
            setSnakbar: 'snakbar/setSnakbar'
        }),

        getRole(){
            this.axios.get(window.location.pathname,this.config)
            .then((ress) => {
                console.log(ress)
                this.lembaga = ress.data.penyalur
                this.total_uang_sisa = ress.data.uang_tersedia
                this.total_uang_beras = ress.data.uang_beras_tersedia
                this.total_beras_sisa = ress.data.sisa_beras

            })
            .catch((err) => console.log(err))
        }

    },

    mixins:[middleware],

    created(){
        this.getRole()
    }
}
