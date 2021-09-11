<template>
    <div class="flex flex-col  " >
        <div class="flex flex-row">
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[0][0].callback_data)">{{boardValues[0][0].text}}</div>
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[0][1].callback_data)">{{boardValues[0][1].text}}</div>			
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[0][2].callback_data)">{{boardValues[0][2].text}}</div>			
        </div>
        <div class="flex flex-row">
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[1][0].callback_data)">{{boardValues[1][0].text}}</div>			
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[1][1].callback_data)">{{boardValues[1][1].text}}</div>			
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[1][2].callback_data)">{{boardValues[1][2].text}}</div>
        </div>
        <div class="flex flex-row">
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[2][0].callback_data)">{{boardValues[2][0].text}}</div>			
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[2][1].callback_data)">{{boardValues[2][1].text}}</div>			
            <div class="bg-white hover:bg-gray-100 text-gray-800 font-semibold   border border-gray-400 rounded shadow w-12 h-12" :class="{'cursor-not-allowed':!turn}" @click="move(boardValues[2][2].callback_data)">{{boardValues[2][2].text}}</div>			
        </div>
    </div>
</template>
<script>
import Button from '../../Jetstream/Button.vue';
export default {
  components: { Button },
    data(){
        return {
            boardValues:[],
            turn:0
        }
    },
    props:{
        state:{},
        newMove:''
    },
    methods:{
        move(data){
        this.$emit('move',data);
        
        },
        newMove(data){
            console.log("Hubo un movimiento");
        }
        
        //  const white = parseInt(data[2], 10);
    //   const black = parseInt(data[3], 10);

    //   for (let i = 0; i < 9; i += 1) {
    // const mask = 1 << i;
    // let piece = ' ';

    // if ((mask & white) != 0) piece = 'O';
    // else if ((mask & black) != 0) piece = 'X';

    // const tile = newElement("div", "unselectable");
    // tile.appendChild(newElement("span", "", piece));
    // tile.id = `${msgId}${i}`;
    // tile.onclick = _ => {
    //   boardMove(msgId, i, `${piece},${i},${white},${black},false,${msgId}`);
    // };

    // board.appendChild(tile);
//   }

    },
    watch:{
            newMove(){
                console.log("VALOR DEL MOVIMIENTO",this.newMove);
                // var data = this.newMove.data.split(",");
                // const posicion = data[1];
                // for (let i = 0; i < 9; i += 1) {
                //     if(i != 0 && (i % 3) == 0) {
                //         if(posicion == i){
                //             console.log("POSICION",i);
                //         }
                //     }
                // }

                // console.log(this.newMove.data);
                var state = this.newMove.data;
                var data = state.split(",");
                const white = parseInt(data[2], 10);
                const black = parseInt(data[3], 10);
                const game_id = data[5];

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

                    //        symbol, idx,      bitmask p1,        bitmask p1,      player type,            game_id
                    var data = `${tile},${i},${white},${black},false,${game_id}`;
                    row.push({"text":tile,"callback_data":data});
                    console.log("DATA...",data);
                }
            
                console.log("Teclado final",board);

                this.boardValues = board;
            }

        },
    created(){
        this.turn = this.state.turn;
            console.log("EL ESTADO DESDE BOARD",this.state);
            console.log("VALORES",this.state);
            console.log("ESTADOS FINALES",JSON.parse(this.state.board_state));

            var a = JSON.parse(this.state.board_state);
            
            this.boardValues = a;

            console.log("Valor 0",a[0][0])
    }
    
}
</script>