require 'spec_helper'

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

  it "has a has no games" do
    click_link('Games')
    expect(page).to have_content('Apologies, but no results were found.')
    #expect(page).to have_css('article.ivanhoe_game')
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
      fill_in 'Username', with: 'admin'
      fill_in 'Password', with: 'admin'
      click_button 'Log In'
      expect(page).to have_link('Log out')
    end

    it "should redirect to to main page" do
      fill_in 'Username', with: 'admin'
      fill_in 'Password', with: 'admin'
      click_button 'Log In'
      expect(page).to_not have_link('Make a Game')
    end

  end

end
