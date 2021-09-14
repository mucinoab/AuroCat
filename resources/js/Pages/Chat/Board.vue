<template>
    <div class="flex flex-col">
        <div class="flex flex-row" v-for="row in boardValues">
            <div 
                class="select-none bg-white hover:bg-gray-200 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12 font-black" 
                v-for="box in row" @click="move(box.callback_data)">{{box.text}}
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            boardValues:[],
            turn:0
        }
    },
    props:{
        state:{},
        newMove:'',
        chat_id:''
    },
    methods:{
        // this method is send to the father(index.vue) to call the move method
        move(data){
            this.$emit('move',data);
        },
        // update the board
        moveLogic(){
            var state = this.newMove.data;
            var data = state.split(",");
            const white = parseInt(data[2], 10);
            const black = parseInt(data[3], 10);
            const game_id = data[5];
            const turn = data[6];

            var board = [];
            var row = [];

            for (let i = 0; i <= 9; i += 1) {
                if(i != 0 && (i % 3) == 0) {
                    // The end of a row
                    board.push(row);
                    row = [];
                }

                var mask = (1 << i);
                var tile = ' ';

                if ((mask & white) != 0) tile = 'O';
                else if ((mask & black) != 0) tile = 'X';

                //          symbol, idx, bitmask p1,bitmask p1,player type,game_id,turn
                var data = `${tile},${i},${white},${black},false,${game_id},${turn}`;
                row.push({"text":tile,"callback_data":data});
                
            }
            this.boardValues = board;
        }
    },
    watch:{
        //reflects changes from parent when there ir a newMove Line:323 in index
        newMove(){
            this.moveLogic();
        },
        // reflects changes in parent property Line:455 in index
        state(){
            this.boardValues = JSON.parse(this.state.state_relation.board_state);
        }
    },
    created() {
        this.boardValues = JSON.parse(this.state.state_relation.board_state);
    }
}
</script>