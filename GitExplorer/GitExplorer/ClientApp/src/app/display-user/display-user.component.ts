import { Component, OnInit } from '@angular/core';
import { SearchService } from '../search.service';
import { ParamMap, ActivatedRoute } from '@angular/router';
import { User } from '../model/user';
import { Repo } from '../model/repo';
import { switchMap } from 'rxjs/operators'
import { Observable } from 'rxjs'
import { RepoSearchService } from '../repo-search.service';

@Component({
  selector: 'app-display-user',
  templateUrl: './display-user.component.html',
  styleUrls: ['./display-user.component.css']
})
export class DisplayUserComponent implements OnInit {

  private user: User
  private repos: Repo[]
  private followers: User[]
  loadingUser: boolean = false
  loadingRepos: boolean = false
  loadingFollowers: boolean = false
  searchFailed: boolean = false

  constructor(private route: ActivatedRoute,
    private searchService: SearchService,
    private repoSearchService: RepoSearchService) { }

  ngOnInit() {

    //prevent loading page until all api calls are finished
    this.loadingUser = true
    this.loadingRepos = true
    this.loadingFollowers = true

    //load user data
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.searchService.getUser(params.get('login')))
    ).subscribe((data: User) => {
      this.user = data
      this.loadingUser = false
      },
      (error: any) => {
        console.log(error)
        this.loadingUser = false
        this.searchFailed = true
      })

    //load users repos
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.repoSearchService.getUserRepos(params.get('login')))
    ).subscribe((data: Repo[]) => {
      this.repos = data
      this.loadingRepos = false
      },
      (error: any) => {
        console.log(error)
        this.loadingRepos = false
        this.searchFailed = true
      })

    //load users followers
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.searchService.getFollowers(params.get('login')))
    ).subscribe((data: User[]) => {
      this.followers = data
      this.loadingFollowers = false
      },
      (error: any) => {
        console.log(error)
        this.loadingFollowers = false
        this.searchFailed = true
      })
  }

}
