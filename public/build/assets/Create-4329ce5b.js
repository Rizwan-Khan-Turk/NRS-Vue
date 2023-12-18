import{p,d as x,q as l,o as f,c as h,w as d,a as e,b as i,i as k,l as a,s}from"./app-737cfb06.js";import{_}from"./AppLayout-c94d3a05.js";const y=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Add Client ",-1),w={class:"p-5 m-5"},v={class:"bg-white dark:bg-gray-900"},V={class:"px-4 mx-auto py-4"},C=["onSubmit"],N={class:"grid gap-4 sm:grid-cols-2 sm:gap-6"},U={class:"sm:col-span-2"},q=e("label",{for:"name",class:"block mb-2 text-xs font-medium text-gray-400 dark:text-white"},"Client Name",-1),A={class:"w-full"},B=e("label",{for:"email",class:"block mb-2 text-xs font-medium text-gray-400 dark:text-white"},"Email",-1),T={class:"w-full"},D=e("label",{for:"phone_number",class:"block mb-2 text-xs font-medium text-gray-400 dark:text-white"},"Phone Number",-1),E={class:"sm:col-span-2"},F=e("label",{for:"address",class:"block mb-2 text-xs font-medium text-gray-400 dark:text-white"},"Address",-1),M={class:"sm:col-span-2"},S=e("label",{for:"notes",class:"block mb-2 text-xs font-medium text-gray-400 dark:text-white"},"Notes",-1),j=e("button",{type:"submit",class:"inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-indigo-900 hover:bg-indigo-800"}," Add Client ",-1),W={__name:"Create",setup(H){const g=p(),o=x({name:"",email:"",phone_number:"",address:"",notes:""}),c=()=>{o.value={name:"",email:"",phone_number:"",address:"",notes:""}},u=async()=>{const n=await axios.post("/clients/store",o.value);c(),g.success(n.data.message)};return(n,r)=>{const m=l("TextElement"),b=l("Vueform");return f(),h(_,{title:"Add Client"},{header:d(()=>[y]),default:d(()=>[e("section",w,[i(b,null,{default:d(()=>[i(m,{name:"hello_world",label:"Hello",placeholder:"World"})]),_:1})]),e("section",v,[e("div",V,[e("form",{onSubmit:k(u,["prevent"])},[e("div",N,[e("div",U,[q,a(e("input",{"onUpdate:modelValue":r[0]||(r[0]=t=>o.value.name=t),type:"text",name:"name",id:"name",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500",placeholder:"John Doe",required:""},null,512),[[s,o.value.name]])]),e("div",A,[B,a(e("input",{"onUpdate:modelValue":r[1]||(r[1]=t=>o.value.email=t),type:"text",name:"email",id:"email",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500",placeholder:"john@email.com",required:""},null,512),[[s,o.value.email]])]),e("div",T,[D,a(e("input",{"onUpdate:modelValue":r[2]||(r[2]=t=>o.value.phone_number=t),type:"text",name:"phone_number",id:"phone_number",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500",placeholder:"71 153 343",required:""},null,512),[[s,o.value.phone_number]])]),e("div",E,[F,a(e("input",{"onUpdate:modelValue":r[3]||(r[3]=t=>o.value.address=t),type:"text",name:"address",id:"address",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500",placeholder:"Downtown, Beirut"},null,512),[[s,o.value.address]])]),e("div",M,[S,a(e("textarea",{"onUpdate:modelValue":r[4]||(r[4]=t=>o.value.notes=t),id:"notes",rows:"8",class:"block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500",placeholder:"Your notes here..."},null,512),[[s,o.value.notes]])])]),j],40,C)])])]),_:1})}}};export{W as default};
