<template>
    <div class="font-bold text-xl select-none text-cat-light-secundary-3 shadow-md py-2 px-6 inline-flex items-center"
        :class="color">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="currentcolor" :d="item"></path>
        </svg>
        <span class="mr-2" >{{title}}</span>
    </div>
</template>

<script>
export default {
    props:{
        game:{}
    },
    computed:{
       date(){
            return timeFromUnix(this.message.date * 1000);
        },
        title(){
            if(this.game.state ==2)
                return 'Finalizado';
            else if(this.game.state_relation.turn == 1)
                return 'Espera Movimiento';
            else
                return 'Tu turno';
        },
        item(){
            if(this.game.state ==2)
                return "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z";
            else if(this.game.state_relation.turn == 1)
                return "M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z";
            else
                return "M2.01 21L23 12 2.01 3 2 10l15 2-15 2z";
        },
        color(){

            if(this.game.state == 2) return 'bg-cat-hard-blue-2';

            if(this.game.state_relation.turn == 1) 
                return 'bg-yellow-500 dark:bg-cat-hard-colors-3';
            else 
                return 'bg-green-500 dark:bg-cat-hard-colors-2';
        }
        
    }
}
</script>