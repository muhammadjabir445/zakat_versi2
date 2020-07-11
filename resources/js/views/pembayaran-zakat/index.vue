<template>
    <v-app>
        <Progress v-if="loading"/>
        <v-container v-if="!loading">
            <v-btn small color="teal darken-2" class="white--text" tile>Data Pembayaran Zakat</v-btn>
            <v-card
            class="border-edit"
            tile
            >
                <v-card-text class="text-center">
                    <v-container>
                        <v-row justify="center" align="center">
                            <v-col
                                cols="6"
                            >
                            <v-text-field
                                v-model="keyword"
                                label="Pencarian"
                                v-on:keyup = "go"
                                color="teal darken-2"
                            ></v-text-field>
                            </v-col>

                            <v-col
                                cols="6"
                                align="right"
                            >
                                <v-btn color="primary"  :to="urlcreate" small tile>
                                    Tambah Data
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>

                    <v-simple-table>
                        <template v-slot:default>
                        <thead>
                            <tr>
                            <th class="text-left">Kode</th>
                            <th class="text-left">Nama Muzaki</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">No HP</th>
                            <th class="text-left">Total Pembayaran</th>
                            <th class="text-left">Jenis Zakat</th>
                            <th class="text-left">Bukti Pembayaran</th>
                            <th class="text-left">Tanggal</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data" :key="item.id">
                                 <td class="text-left">{{item.kode}}</td>
                                <td class="text-left">{{item.nama}}</td>
                                <td class="text-left">{{item.email}}</td>
                                <td class="text-left">{{item.nohp}}</td>
                                <td class="text-left">{{item.total_pembayaran > 1000 ? 'Rp.' + item.total_pembayaran : item.total_pembayaran +' Kg Beras'}}</td>
                                <td class="text-left">{{item.jenis_zakat}}</td>
                                <td class="text-left"><v-btn x-small color="primary" dark v-on:click="dokumentUrl(item.foto)" v-if="item.foto">Dokumen</v-btn>
                                {{item.foto ? '' : 'Pembayaran Offline'}}
                                </td>
                                <td class="text-left">{{item.tgl}}</td>
                                <td class="text-left"><v-btn x-small color="secondary" dark v-if="item.status == 0">Belum Dikonfirmasi</v-btn>
                                <v-btn x-small color="success" dark v-if="item.status == 1">Telah Dikonfirmasi</v-btn>
                                </td>
                                <td class="text-left">
                                    <v-btn x-small color="success" dark v-if="item.status == 0" @click="openDialog(item.id)">Konfirmasi</v-btn>
                                    <v-btn color="success" v-on:click="edit(item.id)" fab x-small dark v-if="!item.foto">
                                        <v-icon>mdi-circle-edit-outline</v-icon>
                                    </v-btn>
                                    <v-btn color="error" fab x-small @click="dialogDelete(item.id)"  v-if="item.status == 0">
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-btn>
                                </td>

                            </tr>
                        </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
                <div class="text-center">
                    <v-pagination
                    v-model="page"
                    :length="lengthpage"
                    :total-visible="7"
                    @input="go"
                    color="teal darken-2"
                    ></v-pagination>
                </div>
                <v-card-actions class="">

                </v-card-actions>
            </v-card>

            <v-dialog
            v-model="dialog"
            max-width="340"
            >
            <v-card>
                <v-card-title class="headline">Apa anda yakin menghapus ?</v-card-title>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn

                    text
                    @click="dialog = false"
                >
                    Cancel
                </v-btn>

                <v-btn
                    color="red"
                    text
                    @click="deleteData()"
                >
                    Delete
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>

            <v-dialog
            v-model="dialog_konfirm"
            max-width="450"
            >
            <v-card>
                <v-card-title class="headline">Konfirmasi Pembayaran ?</v-card-title>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    color="red"
                    text
                    @click="dialog_konfirm=false"
                >
                    Tolak
                </v-btn>

                <v-btn
                    color="success"
                    text
                    @click="confirmasi(1)"
                >

                    Terima
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>

        </v-container>
    </v-app>

</template>
<script>

import CrudMixin from '../../mixins/CrudMixin'
export default {
    name: 'users',
    mixins:[CrudMixin],
    data() {
        return {
            dialog_konfirm:false,
            id_pembayaran:''
        }
    },
    methods:{
            openDialog(id) {
                this.dialog_konfirm = !this.dialog_konfirm
                this.id_pembayaran = id
            },
            async confirmasi(status,id = '') {
            let id_pembayaran = id ? id : this.id_pembayaran
            let url = this.$route.path + '/' + id_pembayaran
             let data = new FormData()
            data.append('status',status)

            data.append('_method','PUT')

            await this.axios.post(url,data,this.config)
            .then((ress) => {
                console.log(ress)
                this.setSnakbar({
                    status:true,
                    pesan:ress.data.message,
                    color:'success'
                })
                this.go()
            })
            .catch((err)=>{
                if (err.response.status == 400 ) {
                    this.setSnakbar({
                    color:'red',
                    status:true,
                    pesan:err.response.data.message,
                    })
                }else{
                    this.setSnakbar({
                    status:true,
                    color:'red',
                    pesan:"Terjadi Kesalahan",
                    })
                }

                console.log(err.response)
            })
            this.dialog_konfirm = false
        }
    }
}
</script>

