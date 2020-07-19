<template>
    <v-app>
        <Progress v-if="loading"/>
        <v-container v-if="!loading">
            <v-btn small color="teal darken-2" class="white--text" tile>Data Dana Zakat</v-btn>
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
                            <th class="text-left">Nama Lembaga</th>
                            <th class="text-left">Alamat Tujuan</th>
                            <th class="text-left">Total Uang</th>
                            <th class="text-left">Total Beras</th>
                            <th class="text-left">Deskripsi</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Petugas</th>
                            <th class="text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data" :key="item.id">
                                <td class="text-left">{{item.penyalur.nama}}</td>
                                <td class="text-left">{{item.penyalur.alamat}}</td>
                                <td class="text-left">{{item.total_uang}}</td>
                                <td class="text-left">{{item.total_beras}}</td>
                                <td class="text-left">{{item.deskripsi}}</td>
                                <td class="text-left">
                                    <v-btn x-small color="secondary" dark v-if="item.status == 0">Belum diapproval</v-btn>
                                    <v-btn x-small color="success" dark v-if="item.status == 1">Sudah diapproval</v-btn>
                                    <v-btn x-small color="orange" dark v-if="item.status == 2">Telah dicairkan</v-btn>
                                    <v-btn x-small color="purple" dark v-if="item.status == 3">Sedang diantarkan</v-btn>
                                    <v-btn x-small color="success" dark v-if="item.status == 4">Dana telah diterima</v-btn>
                                    <v-btn x-small color="red" dark v-if="item.status == 5">Ditolak</v-btn>
                                </td>
                                <td class="text-left">{{item.petugas.name}}</td>
                                <td class="text-left">
                                    <v-btn x-small color="success" dark v-if="item.status == 0 && (user.id_role == 34 || user.id_role == 23)" @click="openDialog(item.id,0)">Konfirmasi</v-btn>
                                    <v-btn x-small color="success" dark v-if="item.status == 1 && (user.id_role == 37 || user.id_role == 23)" @click="openDialog(item.id,2)">Cairkan</v-btn>
                                    <v-btn x-small color="success" dark v-if="item.status == 2 && (user.id_role == 35 || user.id_role == 23)" @click="openDialog(item.id,3)">Antar</v-btn>
                                    <v-btn x-small color="success" dark v-if="item.status == 3 && (user.id_role == 36 || user.id_role == 23)"@click="openDialog(item.id,4)">Terima</v-btn>
                                    <v-btn color="success" v-on:click="edit(item.id)" fab x-small dark v-if="item.status == 0  && (user.id_role == 35 || user.id_role == 23)">
                                        <v-icon>mdi-circle-edit-outline</v-icon>
                                    </v-btn>
                                    <v-btn color="error" fab x-small @click="dialogDelete(item.id)"  v-if="(item.status == 0 || item.status == 5) && (user.id_role == 35 || user.id_role == 23)">
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
                <v-card-title class="headline">{{pesan_dialog}}</v-card-title>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    color="red"
                    text
                    @click="status == 0 ? confirmasi(5) : dialog_konfirm = false"

                >
                    {{status == 0 ? 'Tolak' : 'Cancel'}}
                </v-btn>



                <v-btn
                    color="success"
                    text
                    @click="status == 0 ? confirmasi(1) : confirmasi()"
                >

                    {{status == 0 ? 'Terima' : 'Ya'}}
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-container>
    </v-app>

</template>
<script>

import crud_dana from '../../mixins/crud_dana'
export default {
    name: 'users',
    data() {
        return {
            dialog_konfirm:false,
            id_penyalur:'',
            status:0
        }
    },
    mixins:[crud_dana],
    computed: {
        pesan_dialog:{
            get(){
                if (this.status == 0) {
                    return 'Konfirmasi pengajuan dana'
                } else if(this.status == 2) {
                    return 'Cairkan dana ?'
                } else if(this.status == 3) {
                    return 'Antarkan dana'
                } else if(this.status == 4) {
                    return 'dana telah diterima'
                }
            }
        }
    },
    methods: {
        openDialog(id,status) {
            this.dialog_konfirm = !this.dialog_konfirm
            this.id_penyalur = id
            this.status = status
        },
        async confirmasi(status = null,id = null) {
            let id_penyalur = id ? id : this.id_penyalur
            let url = this.$route.path + '/' + id_penyalur
             let data = new FormData()
            data.append('status', status ? status : this.status)

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

