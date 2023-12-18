import{T as g,k as h,o as r,e as u,b as e,u as t,Z as _,w as o,h as p,a as s,n as y,f as n,j as l,i as v,F as x}from"./app-737cfb06.js";import{_ as b,A as k}from"./AuthenticationCardLogo-84442d3e.js";import{_ as w}from"./PrimaryButton-f740c22b.js";const V=s("div",{class:"mb-4 text-sm text-gray-600"}," Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1),E={key:0,class:"mb-4 font-medium text-sm text-green-600"},B=["onSubmit"],C={class:"mt-4 flex items-center justify-between"},F={__name:"VerifyEmail",props:{status:String},setup(c){const d=c,i=g({}),f=()=>{i.post(route("verification.send"))},m=h(()=>d.status==="verification-link-sent");return(a,N)=>(r(),u(x,null,[e(t(_),{title:"Email Verification"}),e(k,null,{logo:o(()=>[e(b)]),default:o(()=>[V,m.value?(r(),u("div",E," A new verification link has been sent to the email address you provided in your profile settings. ")):p("",!0),s("form",{onSubmit:v(f,["prevent"])},[s("div",C,[e(w,{class:y({"opacity-25":t(i).processing}),disabled:t(i).processing},{default:o(()=>[n(" Resend Verification Email ")]),_:1},8,["class","disabled"]),s("div",null,[e(t(l),{href:a.route("profile.show"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:o(()=>[n(" Edit Profile")]),_:1},8,["href"]),e(t(l),{href:a.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2"},{default:o(()=>[n(" Log Out ")]),_:1},8,["href"])])])],40,B)]),_:1})],64))}};export{F as default};