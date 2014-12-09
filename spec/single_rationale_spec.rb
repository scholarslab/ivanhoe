require 'spec_helper'

describe 'Make a Role View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        make_game
        first('.game-title a').click
        make_role
        make_a_move
        within('#game-data article.role') do
            first('a').click
        end
        within('.rationales') do
            first('a').click
        end
    end

    it 'has the rationale title' do
      expect(page).to have_content('Journal Entry for')
      expect(page).to have_selector('article h1')
    end

    it 'has the rationale content' do
      expect(page).to have_selector('article p', :match => :first)
    end

end
