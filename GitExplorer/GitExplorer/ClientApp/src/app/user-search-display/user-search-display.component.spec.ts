import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserSearchDisplayComponent } from './user-search-display.component';

describe('UserSearchDisplayComponent', () => {
  let component: UserSearchDisplayComponent;
  let fixture: ComponentFixture<UserSearchDisplayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserSearchDisplayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserSearchDisplayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
