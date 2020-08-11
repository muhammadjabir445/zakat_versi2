<template>
    <div>
    <v-container>
        <v-row>
            <v-col
            cols="12"
            md="4"
            >
                <v-card
                class="border-edit"
                tile

                >
                <v-btn small color="teal darken-2" class="white--text" tile>Sisa Uang Tersedia</v-btn>
                    <v-card-text class="text-center">
                        <h3>Rp. {{total_uang_sisa | numberFormat}}</h3>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
            </v-card>
            </v-col>

            <v-col
            cols="12"
            md="4"
            >
                <v-card
                class="border-edit"
                tile

                >
                <v-btn small color="teal darken-2" class="white--text" tile>Total Sisa Beras</v-btn>
                    <v-card-text class="text-center">

                     <h3>{{total_beras_sisa}} Kg</h3>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
            </v-card>
            </v-col>

            <v-col
            cols="12"
            md="4"
            >
                <v-card
                class="border-edit"
                tile

                >
                <v-btn small color="teal darken-2" class="white--text" tile>Total Sisa uang beras</v-btn>
                    <v-card-text class="text-center">
                    <h3>Rp. {{total_uang_beras |numberFormat}}</h3>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
            </v-card>
            </v-col>


        </v-row>

         <v-card
                class="border-edit"
                tile

                >
                <v-btn small color="teal darken-2" class="white--text" tile>BMT At-Taqwa</v-btn>
                    <v-card-text class="text-center">
                    <v-img src="https://attaqwakemanggisan.files.wordpress.com/2013/07/msjid-attaqwa21.jpg" aspect-ratio="1.7"></v-img>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
            </v-card>
    </v-container>

    </div>
</template>
<script>
import {mapActions} from 'vuex'
import middleware from '../mixins/middleware'
export default {
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,
        jenis_pengajuan:'',
        total_uang_sisa : 0,
        total_uang_beras : 0,
        total_beras_sisa : 0,

      }),
    methods: {
        ...mapActions({
            setSnakbar: 'snakbar/setSnakbar'
        }),

        getRole(){
            this.axios.get('/dana-zakat/create',this.config)
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

    filters:{
        numberFormat(value){
                 let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }
    },

    mixins:[middleware],

    created(){
        this.getRole()
    }
}

</script>
