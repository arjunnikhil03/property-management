import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { AppData } from './models/app';
import { AuthService } from './service/auth.service';
import { Router } from '@angular/router';
import { Observable } from 'rxjs/internal/Observable';
import { Location } from '@angular/common';
import { updateAppData } from './models/app.action';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  title = 'property-management';
  authCheck:boolean = false;
  appData$ :Observable<AppData> | undefined;
  isAuth:boolean = false;

  constructor(
    //private connectionService:ConnectionService,
    private location:Location,
    private authService:AuthService,
    public router:Router,
    private store: Store<{ appItem: AppData }>){
    }

  ngOnInit(): void {
    this.isAuth = (this.location.path() === '/login')?false:true;
    this.router.events.subscribe(event => {
      //console.log(this.location.path());
      this.isAuth = (this.location.path() === '/login')?false:true;
    });
  }

  logout(){
    let appData:AppData = {
      isAuth:false,
      token:'',
      CustomerToken:{}
    }
    this.authService.logout();
    this.store.dispatch(updateAppData(appData))
    this.router.navigateByUrl("/login");
    return;
  }
}
