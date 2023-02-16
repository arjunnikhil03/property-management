import {  Component, OnInit,ViewChild } from '@angular/core';
import {  AbstractControl, FormControl, FormGroup, Validators } from '@angular/forms';
import { Login,LoginType, MerchantValidateResponse, PageName, SmRsmLogin } from './../models/app';
import { NgbAlert, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import { AuthService } from '../service/auth.service';
import { Router } from '@angular/router';
//import { Observable } from 'rxjs';
import { AppData } from '../models/app';
import { Store } from '@ngrx/store';
import { updateAppData } from '../models/app.action';
import { ApiService } from '../service/api.service';
//import { HttpErrorResponse } from '@angular/common/http';
//import { LoaderComponent } from '../loader/loader.component';
import { debounceTime, Subject } from 'rxjs';
import * as Forge from 'node-forge';
import { LoaderComponent } from '../loader/loader.component';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit  {
    showPassword = false;
    login: Login  = {
      username: "",
      password: ""
    };

    loginForm = new FormGroup({
      username: new FormControl(this.login?.username),
      password: new FormControl(this.login?.password)
    });

    disabled:Boolean=true;
    submitted:boolean= false;
    error:boolean = false;
    errorObj:any=null;
    
    //@ViewChild('selfClosingErrorAlert', {static: false}) 
    //selfClosingErrorAlert: NgbAlert;
    
    invalidMessage = ""
    RSA_PUBLIC_KEY = `LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlHZk1BMEdDU3FHU0liM0RRRUJBUVVBQTRHTkFE
    Q0JpUUtCZ1FDcHZmVy9HK1J4ZUlKaHhZS0pFaEZhbkFlbwoydnhEWjFLY3VNa1cyc29Ya1VBWHk0
    Nm40cThlWko2VEF3VDFyR04zSEphVEMzUG5hLzU1eHNDL1ovWmVTN21UCjZzY0VIL2tBdyt2aWhB
    bjNnUW5hRUZMZkpYcU9ObGRqV2lwdVUvWEphbkdCVzlybkFiMDIxVnhQbDJoR1oyeC8KbU5tQW1j
    eWZqZlhzYWZ4MmR3SURBUUFCCi0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQo=`;

    private _error = new Subject<string>();
    messageTimeOutInSeconds = 10000;

    public changeErrorMessage(msg:String) { this._error.next(msg.toString()); }

    toggleShowPassword(){
        this.showPassword = !this.showPassword;
        //this.loginForm.controls.smRsm.controls.password.= (this.showPassword)?'text':'password';
    }
    
    
    ngOnInit(): void {
      this._error.subscribe(message => this.invalidMessage = message);
    }
    
    constructor(private modalService: NgbModal,
                private apiService:ApiService,
                private router: Router,
                private store: Store<{ appItem: AppData }>) {}

    get loginFormControl() {
      return this.loginForm.controls;
    }
    
    onSubmit() {
      //console.log(this.loginForm)
      this.submitted = true;
      if(this.loginForm.valid){
        this.login = <Login> this.loginForm.value;

        const loaderRef = this.modalService.open(LoaderComponent,{
          centered: true,
          animation:true,
          backdrop:'static',
          keyboard: false,
          windowClass:"remove-bg-modal",
         size:"sm",
        // modalDialogClass: " modal-dialog-centered d-flex justify-content-center"
        });
        //return;
        /*
        if(this.login.loginType == LoginType.SALESEXECUTIVE){
          this.login.fromPage = PageName.LoginPage;
          
          this.apiService.merchantValidate(this.login).subscribe((data: any)=>{
              loaderRef.close();
              let appData:AppData={
                  transactionID : data?.transactionID.toString(),
                  isAuth: false,
                  token:'',
                  loginType: this.login.loginType,
                  userId: this.login.salesExecutive?.mobileNumber
              }
              this.login.displayOverlay = true;
              this.login.appData = appData;
              this.login.dispatchAppData = true;
              //this.login.fromPage = PageName.LoginPage;
              const modalRef = this.modalService.open(OtpComponent,{
                centered: true
              });
              modalRef.componentInstance.login = this.login;
          },(err)=>{
              loaderRef.close();
              this.disabled = true;
              this.error = true;
              if(err.error.error || err.error.errors){
                if(err.error?.error){
                  this.changeErrorMessage(err.error.error);
                }
                if(err.error?.errors){
                  err.error?.errors.forEach((_m: String) => {
                    this.changeErrorMessage(_m);
                  });
                }
              }else{
                this.changeErrorMessage(err.message);
              }
              //}
              //console.log(err);
          })
          
        }

        if(this.login.loginType  == LoginType.SMRSM){
          let sm: SmRsmLogin = {};
          sm.EntityUserName = this.login.smRsm?.sapId;
          let password = this.login.smRsm?.password || "";
          
          //const publicKey = Forge.pki.publicKeyFromPem(this.RSA_PUBLIC_KEY);
          const publicKey = Forge.pki.publicKeyFromPem(Forge.util.decode64(this.RSA_PUBLIC_KEY));
          const encryptedPassword = publicKey.encrypt(password.toString(),'RSAES-PKCS1-V1_5');
          sm.EntityUserPassword = Forge.util.encode64(encryptedPassword);
          //sm.EntityUserPassword = .toStringencryptedText();
          this.apiService.ldapAuthenticate(sm).subscribe((data:any)=>{
            let appData:AppData = {};
            appData.transactionID ="";
            appData.isAuth= true;
            appData.token = data.token;
            appData.loginType= this.login.loginType;
            appData.userId= sm.EntityUserName;
            loaderRef.close();
            this.store.dispatch(updateAppData(appData));
            this.router.navigateByUrl("/home");
          },(err)=>{
            //console.log("i am here",err);
            loaderRef.close();
            this.disabled = true;
            this.error = true;
            if(err.error.error || err.error.errors){
              //this.errorObj = err.error;
              this.changeErrorMessage(err.error.error);
            }else{
              //this.errorObj = {error: err.message};
              this.changeErrorMessage(err.message);
            }
          })
          
        }*/

      }
    }

    encryptPassword(event:any){
      if(event.target.value.length > 0){
        let sm: SmRsmLogin = {};
        sm.EntityUserPassword = event.target.value;
        this.apiService.encryptPassword(sm).subscribe((data:any)=>{
          let value = (data.HashValue)?data.HashValue:'';
          //this.loginFormControl.smRsm.controls.password.setValue(value);
          //console.log(this.loginForm.valid );
          //this.smRsmLoginBtn = !this.loginForm.valid;
          },(err:any)=>{
            //this.smRsmLoginBtn = false;
          //this.loginFormControl.smRsm.controls.password.setValue('');
        })
      }
      //console.log(event.target.value);
    }
  

}
