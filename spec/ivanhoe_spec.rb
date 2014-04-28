require 'spec_helper'

#URL_BASE = "http://localhosd:8888/ivanhoe"

describe 'The Ivanhoe theme', :type => :feature, :js => true do

  before :each do
    visit URL_BASE
  end

  it 'should be installed as a theme' do
    img = find('footer').find('img')
    img[:src].should have_content('ivanhoelogo.png')
  end

  it "has a link to 'Log in'" do
    expect(page).to have_link("Log in")
  end

  it "has a link to 'Games'" do
    expect(page).to have_link('Games')
  end

  it "has a link to 'Log in'" do
    expect(page).to have_link('Log in')
  end

  it "has a list of games" do
    click_link('Games')
    find('article.game')
    expect(page).to have_content
  end

  describe "authenticated use" do

    before :each do
      click_link('Log in')
    end

    it "has an error page" do
      fill_in 'Username', with: 'foo'
      fill_in 'Password', with: 'bar'
      click_button 'Log In'
      expect(page).to have_content('ERROR')
    end

    it "should be able to log in" do
      fill_in 'Username', with: ENV['VALID_USER']
      fill_in 'Password', with: ENV['VALID_PASSWORD']
      click_button 'Log In'
      expect(page).to have_link('Log out')
    end

    it "should redirect to to main page" do
      fill_in 'Username', with: ENV['VALID_USER']
      fill_in 'Password', with: ENV['VALID_PASSWORD']
      click_button 'Log In'
      expect(page).to_not have_link('Make a Game')
    end

  end

end
