import auth from "@/store/modules/auth";
import { createStore } from 'vuex'
import VuexPersistence from 'vuex-persist'
import manageuser from "@/store/modules/manageuser"
import integration from "@/store/modules/integration"
import zap from "@/store/modules/zap"
let stores = "";
stores = {
    auth,
    manageuser,
    integration,
    zap
}
const vuexLocal = new VuexPersistence({
  modules: ['auth']
  })
/**
 * @desc Inject module in store
 */
//  const dataState = createPersistedState({
//   paths: ['auth','general.theme_dark']
// })
export default createStore({
    modules: stores,
    plugins:[vuexLocal.plugin],
})
  