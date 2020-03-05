import Profile from './components/users/Profile.vue'
import Advert from './components/users/Advert.vue';
import AdvertAdd from './components/users/AdvertAdd.vue';
import AdvertEdit from './components/users/AdvertEdit.vue';

export const routes = [
    {
        path:'/user/profile',
        component:Profile
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert/add',
        component:AdvertAdd
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert',
        component:Advert
    },
    {
        path:'/user/advert/edit/:id',
        component:AdvertEdit
    },

];
