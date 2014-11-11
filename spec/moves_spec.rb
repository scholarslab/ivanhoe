require 'spec_helper'

describe "An Ivanhoe move", :type => :feature, :js => true do

  before :each do
    visit URL_BASE
    click_link('Games')
    click_link('Log in')
    fill_in 'Username', with: ENV['VALID_USER']
    fill_in 'Password', with: ENV['VALID_PASSWORD']
    click_button 'Log In'

    #first('.game-title > a').click

    #find('article.game').first(:link).click
    #first('.move h1 > a').click
  end

  it "has a link for moves" do
    # save_and_open_page
  end

  # TODO need to construct a game before these tests will pass

  #it "should have a the required classes" do
  #expect(page).to have_css('body.single-ivanhoe_move')
  #expect(page).to have_css('.single-move')
  #expect(page).to have_css('.source-response-container')
  #expect(page).to have_css('.discussion-source')
  #expect(page).to have_css('.discussion-response')
  #expect(page).to have_css('.game-description')
  #end

  #describe "header content" do

  #before :each do
  #page.find('.single-move')
  #end

  #it "has a header" do
  #expect(page).to have_selector('header h1')
  #end

  #it "has a 'byline'" do
  #expect(page).to have_selector('header .byline')
  #end

  #it "has a date" do
  #expect(page).to have_selector('header time')
  #end

  #it "has a link back to the game" do
  #expect(page).to have_link('Return to game')
  #end
  #end

end
