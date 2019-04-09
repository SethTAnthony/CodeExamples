import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RepoSearchDisplayComponent } from './repo-search-display.component';

describe('RepoSearchDisplayComponent', () => {
  let component: RepoSearchDisplayComponent;
  let fixture: ComponentFixture<RepoSearchDisplayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RepoSearchDisplayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RepoSearchDisplayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
