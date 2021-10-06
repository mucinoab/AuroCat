<template>
    <div class="col-span-12 sm:col-span-6 md:col-span-3">
        <div class="flex flex-row bg-white shadow-sm rounded p-4 dark:bg-cat-hard-5">
            <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl"
                :class="option.color">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2" 
                        :d="option.icon" />
                </svg>
            </div>
            <div class="flex flex-col flex-grow ml-4">
              <div class="text-base text-gray-500 dark:text-cat-gray-5">{{option.title}}</div>
              <div class="font-bold text-xl dark:text-cat-light-secundary-3">{{text}}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            opponent:''
        }
    },
    props:{
        game:'',
        option:'',
        name:''
    },
    computed:{
        text(){
            if(this.option.option == 0){
                return this.game.state != 2 ? 'En curso' : 'Finalizada';
            }else if(this.option.option == 1){
                return this.game.opponent ? 'Agente' : 'Bot';
            }else if(this.option.option == 2){
                if(this.game.winner!=undefined){
                    if(this.game.winner == 0){
                        return this.game.opponent ? 'Agente' : 'Bot';
                    }else if(this.game.winner == 1){
                        return this.name;
                    }else if(this.game.winner == 2){
                        return 'Empate';
                    }
                }
            }
        }  
    }
}
</script>