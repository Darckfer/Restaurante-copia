document.getElementById("user").onblur = function validaUser(){
    let user = this.value
    let errorUser = ""
    if(user.length == 0 || user == null || /^\s+$/.test(user)){
        errorUser = "El campo no puede estar vacio."
    } else if(!letras(user)){
        errorUser = "El campo solo debe tener letras."
    }
    function letras(user){
        let letra = /^[a-zA-Z]+$/
        return letra.test(user)
    }
    document.getElementById("errorUser").innerHTML = errorUser
}
document.getElementById("pwd").onblur = function validaPwd(){
    let pwd = this.value
    let errorPwd = ""
    if(pwd.length == 0 || pwd == null || /^\s+$/.test(pwd)){
        errorPwd = "El campo no puede estar vacio."
    } else if(!patron(pwd)){
        errorPwd = "El campo necesita letra mayúscula, minúscula y número."
    }
    function patron(pwd){
        let patron = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}/
        return patron.test(pwd)
    }
    document.getElementById("errorPwd").innerHTML = errorPwd
}