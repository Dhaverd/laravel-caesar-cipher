<template>
    <v-card class="bg-gradient" style="height: 100%">
        <v-card-text class="d-flex justify-center align-center">
            <v-card title="Code" id="code-form" class="align-center justify-center h-auto w-33">
                <v-card-text>
                    <v-text-field id="code-text" label="Text" variant="outlined" :rules="rules" v-model="codeText"></v-text-field>
                    <v-text-field id="code-shift" label="Shift" variant="outlined" :rules="rulesNum" v-model="codeShift"></v-text-field>
                    <v-switch
                        v-model="codeLang"
                        color="primary"
                        hide-details
                        true-value="Ru"
                        false-value="En"
                        :label="`Language: ${codeLang}`"
                    ></v-switch>
                    <v-text-field loading id="code-result" label="Result" variant="outlined" v-model="codeOutput">
                        <template v-slot:loader>
                            <v-progress-linear
                                :active="isLoadingCode"
                                color="warning"
                                height="3"
                                indeterminate
                            ></v-progress-linear>
                        </template>
                    </v-text-field>
                    <div style="text-align: center"><v-btn @click="caesarCipher(0)" class="customBtn">Code</v-btn></div>
                </v-card-text>
                <!--<v-card-actions><v-btn>Code</v-btn></v-card-actions>-->
            </v-card>
            <v-card title="Decode" id="decode-form" class="align-center justify-center h-auto w-33">
                <v-card-text>
                    <v-text-field id="decode-text" label="Text" variant="outlined" :rules="rules" v-model="decodeText"></v-text-field>
                    <v-text-field id="decode-shift" label="Shift" variant="outlined" :rules="rulesNum" v-model="decodeShift"></v-text-field>
                    <v-switch
                        v-model="decodeLang"
                        color="primary"
                        hide-details
                        true-value="Ru"
                        false-value="En"
                        :label="`Language: ${decodeLang}`"
                    ></v-switch>
                    <v-text-field id="decode-result" label="Result" variant="outlined" v-model="decodeOutput">
                        <template v-slot:loader>
                            <v-progress-linear
                                :active="isLoadingDecode"
                                color="warning"
                                height="3"
                                indeterminate
                            ></v-progress-linear>
                        </template>
                    </v-text-field>
                    <div style="text-align: center"><v-btn @click="caesarCipher(1)" class="customBtn">Decode</v-btn></div>
                </v-card-text>
            </v-card>
        </v-card-text>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    name: "Login",
    data: () => ({
        rules: [
            value => !!value || 'Required.',
        ],
        rulesNum: [
            value => !!value || 'Required.',
            value => /^\d+$/.test(value) || 'Digits only',
            value => parseInt(value) >= 0 || 'Shift must be > 0'
        ],
        codeOutput: '',
        decodeOutput: '',
        codeText: '',
        codeShift: 0,
        decodeText: '',
        decodeShift: 0,
        codeLang: 'En',
        decodeLang: 'En',
        isLoadingCode: false,
        isLoadingDecode: false
    }),
    methods: {
        caesarCipher(cd){
            async function getResp (cdNum, text, shift, language){
                let resp = await axios.get('/api/v1', { params: { cd: cdNum, text: text, shift: shift, language: language } });
                return resp.data;
            }

            if (cd === 0){
                this.isLoadingCode = true;
                this.codeOutput = '';
                getResp(cd, this.codeText, this.codeShift, this.codeLang).then( (res) => {
                    this.codeOutput = res;
                    this.isLoadingCode = false;
                });
            } else if (cd === 1){
                this.isLoadingDecode = true;
                this.decodeOutput = '';
                getResp(cd, this.decodeText, this.decodeShift, this.decodeLang).then((res) => {
                    this.decodeOutput = res;
                    this.isLoadingDecode = false;
                });
            }
        },
    }
}
</script>

<style scoped>
.bg-gradient {
    background: linear-gradient(-45deg, #6947ea, #ffd298, #4698bb);
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
    height: 100vh;
}

.customBtn {
    background-color: #0d47a1;
    color: mintcream;
}

#code-form {
    margin-right: 2em;
}
</style>
