<template>
    <v-app>
        <v-row justify="center">

            <v-col
            cols="12"
            md="8"
            >
            <v-container v-if="!data">
                <v-card
                class="border-edit"
                tile
                >
                    <v-btn small color="teal darken-2" class="white--text" tile>Cek Pembayaran</v-btn>
                    <v-card-text class="">
                    <v-card
                    class="border-edit"
                    v-if="color"
                    :color="color"
                    >
                        <v-card-text class="">
                            <h2>Kode yang anda masukkan salah silakan coba lagi</h2>
                        </v-card-text>

                    </v-card>
                    <v-form action="">
                        <v-text-field
                        label="Kode Pembayaran"
                        outlined
                        color="teal darken-2"
                        v-model="kode"
                    ></v-text-field>
                    <v-row>
                            <v-col
                            cols="12"
                            align="right"

                            >
                              <v-btn
                              block
                              color="teal darken-2"
                              class="white--text"
                              @click="get()"
                                >
                                Cek
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>


                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
                </v-card>
            </v-container>

            <v-container v-if="data">
                <v-card
                class="border-edit"
                tile

                >
                    <v-btn small color="teal darken-2" class="white--text" tile>Cek Pembayaran</v-btn>
                    <v-card-text class="">

                    <table >
                        <tbody>
                            <tr>
                                <td>Kode Pembayaran</td>
                                <td>: {{data.kode}}</td>
                            </tr>

                            <tr>
                                <td>Jenis Zakat</td>
                                <td>: {{data.jenis_zakat}}</td>
                            </tr>

                            <tr>
                                <td>Tanggal Pembayaran</td>
                                <td>: {{data.tgl}}</td>
                            </tr>

                            <tr>
                                <td>Total pembayaran</td>
                                <td>: {{data.total_pembayaran > 1000 ? 'Rp' + data.total_pembayaran : data.total_pembayaran + 'KG Beras'}}</td>
                            </tr>

                            <tr>
                                <td>Lokasi Pembayaran</td>
                                <td>: {{data.foto ? 'Dilakukan Secara Online' : 'Dilakukan di BMT At-Taqwa Langsung'}}</td>
                            </tr>

                            <tr>
                                <td>Status</td>
                                <td>
                                 <v-btn x-small color="primary" dark v-if="data.status == 0 ">Proses</v-btn>
                                 <v-btn x-small color="success" dark v-if="data.status == 1 ">Telah Dikonfirmasi Oleh Petugas Zakat</v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
                </v-card>
            </v-container>

            </v-col>

        </v-row>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            data:'',
            color:'',
            kode: ''
        }
    },

    methods:{
        async get(){
            let url = '/cek-status-pembayaran?kode=' +this.kode
            await this.axios.get(url)
            .then((ress)=>{
                console.log(ress)
                this.data = ress.data.data
                this.color = ''

            })
            .catch((err) => {
                this.color = 'red'
            })
        }
    }
}
</script>


<style lang="css" scoped>
     td{
        padding: 10px;
    }
</style>
